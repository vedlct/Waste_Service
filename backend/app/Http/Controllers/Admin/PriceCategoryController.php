<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Admin\Concerns\GuardsDeletions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePriceCategoryRequest;
use App\Http\Requests\Admin\UpdatePriceCategoryRequest;
use App\Models\PriceCategory;

class PriceCategoryController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;
    use GuardsDeletions;

    public function index()
    {
        return view('admin.price-categories.index');
    }

    public function data()
    {
        return $this->adminDataTable(
            PriceCategory::query()
                ->with('image:id,disk,path,mime_type,alt_text,original_name')
                ->withCount(['items', 'items as placeholder_items_count' => fn ($query) => $query->where('pricing_status', 'placeholder')])
                ->select(['id', 'image_id', 'slug', 'name', 'eyebrow', 'is_active', 'sort_order'])
        )
            ->addColumn('preview', fn (PriceCategory $category) => $category->image
                ? $this->renderAdminPartial('admin.media.partials.preview', ['media' => $category->image])
                : '<span class="text-muted small">No image</span>')
            ->addColumn('category', fn (PriceCategory $category) => $this->renderAdminPartial('admin.price-categories.partials.name', compact('category')))
            ->editColumn('is_active', fn (PriceCategory $category) => $this->renderStatusBadge(
                $category->is_active ? 'Active' : 'Inactive',
                $category->is_active ? 'active' : 'muted',
            ))
            ->addColumn('items', fn (PriceCategory $category) => $this->renderAdminPartial('admin.price-categories.partials.items', compact('category')))
            ->addColumn('actions', fn (PriceCategory $category) => $this->renderAdminPartial('admin.price-categories.partials.actions', compact('category')))
            ->rawColumns(['preview', 'category', 'is_active', 'items', 'actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.price-categories.create', [
            'category' => new PriceCategory(['is_active' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(StorePriceCategoryRequest $request)
    {
        PriceCategory::create($request->validated());

        return $this->success(redirect()->route('admin.price-categories.index'), 'Price category created successfully.');
    }

    public function edit(PriceCategory $priceCategory)
    {
        return view('admin.price-categories.edit', ['category' => $priceCategory]);
    }

    public function update(UpdatePriceCategoryRequest $request, PriceCategory $priceCategory)
    {
        $priceCategory->update($request->validated());

        return $this->success(redirect()->route('admin.price-categories.index'), 'Price category updated successfully.');
    }

    public function destroy(PriceCategory $priceCategory)
    {
        // service_items cascades on delete, so a category with items is never removed silently.
        $blocked = $this->deletionBlockedMessage('This price category', [
            'Service items' => $priceCategory->items()->withTrashed()->count(),
        ]);

        if ($blocked !== null) {
            return $this->error(back(), $blocked);
        }

        $priceCategory->delete();

        return $this->success(redirect()->route('admin.price-categories.index'), 'Price category deleted successfully.');
    }
}
