<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceItemRequest;
use App\Http\Requests\Admin\UpdateServiceItemRequest;
use App\Models\PriceCategory;
use App\Models\ServiceItem;
use App\Support\Money;
use Illuminate\Http\Request;

class ServiceItemController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.service-items.index', [
            'categories' => PriceCategory::query()->ordered()->get(['id', 'name']),
            'placeholderCount' => ServiceItem::query()->withPricingStatus('placeholder')->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = ServiceItem::query()
            ->with(['priceCategory:id,name', 'image:id,disk,path,mime_type,alt_text,original_name'])
            ->select(['id', 'price_category_id', 'image_id', 'sku', 'slug', 'name', 'price_pence', 'vat_rate_basis_points', 'pricing_status', 'is_active', 'sort_order'])
            ->when($request->filled('price_category_id'), fn ($builder) => $builder->where('price_category_id', $request->integer('price_category_id')))
            ->when($request->filled('pricing_status'), fn ($builder) => $builder->where('pricing_status', $request->string('pricing_status')->toString()));

        return $this->adminDataTable($query)
            ->addColumn('preview', fn (ServiceItem $item) => $item->image
                ? $this->renderAdminPartial('admin.media.partials.preview', ['media' => $item->image])
                : '<span class="text-muted small">No image</span>')
            ->addColumn('item', fn (ServiceItem $item) => $this->renderAdminPartial('admin.service-items.partials.name', compact('item')))
            ->addColumn('category', fn (ServiceItem $item) => $item->priceCategory?->name ?? 'Uncategorised')
            ->editColumn('price_pence', fn (ServiceItem $item) => $this->renderAdminPartial('admin.service-items.partials.price', compact('item')))
            ->editColumn('pricing_status', fn (ServiceItem $item) => $this->renderAdminPartial('admin.partials.pricing-status-badge', ['status' => $item->pricing_status]))
            ->editColumn('is_active', fn (ServiceItem $item) => $this->renderStatusBadge(
                $item->is_active ? 'Active' : 'Inactive',
                $item->is_active ? 'active' : 'muted',
            ))
            ->addColumn('actions', fn (ServiceItem $item) => $this->renderAdminPartial('admin.service-items.partials.actions', compact('item')))
            ->rawColumns(['preview', 'item', 'price_pence', 'pricing_status', 'is_active', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $item = new ServiceItem([
            'pricing_status' => 'placeholder',
            'is_active' => true,
            'sort_order' => 0,
            'vat_rate_basis_points' => config('pricing.default_vat_basis_points', 2000),
        ]);

        return view('admin.service-items.create', $this->formData($item));
    }

    public function store(StoreServiceItemRequest $request)
    {
        ServiceItem::create($request->serviceItemAttributes());

        return $this->success(redirect()->route('admin.service-items.index'), 'Service item created successfully.');
    }

    public function edit(ServiceItem $serviceItem)
    {
        return view('admin.service-items.edit', $this->formData($serviceItem));
    }

    public function update(UpdateServiceItemRequest $request, ServiceItem $serviceItem)
    {
        $serviceItem->update($request->serviceItemAttributes());

        return $this->success(redirect()->route('admin.service-items.index'), 'Service item updated successfully.');
    }

    public function destroy(ServiceItem $serviceItem)
    {
        // Soft deleted so booking item snapshots and historical reports stay intact.
        $serviceItem->delete();

        return $this->success(redirect()->route('admin.service-items.index'), 'Service item deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(ServiceItem $item): array
    {
        return [
            'item' => $item,
            'categories' => PriceCategory::query()->ordered()->get(['id', 'name']),
            'pricePounds' => Money::toPounds($item->price_pence),
            'vatPercent' => Money::basisPointsToPercent($item->vat_rate_basis_points ?? config('pricing.default_vat_basis_points', 2000)),
        ];
    }
}
