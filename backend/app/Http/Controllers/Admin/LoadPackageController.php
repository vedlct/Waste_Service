<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLoadPackageRequest;
use App\Http\Requests\Admin\UpdateLoadPackageRequest;
use App\Models\LoadPackage;
use App\Support\Money;

class LoadPackageController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.load-packages.index', [
            'placeholderCount' => LoadPackage::query()->withPricingStatus('placeholder')->count(),
        ]);
    }

    public function data()
    {
        return $this->adminDataTable(
            LoadPackage::query()->select([
                'id', 'slug', 'name', 'price_inc_vat_pence', 'price_ex_vat_pence', 'vat_rate_basis_points',
                'max_weight_kg', 'volume_cubic_yards', 'sack_equivalent', 'loading_time_minutes',
                'pricing_status', 'is_popular', 'is_active', 'sort_order',
            ])
        )
            ->addColumn('package', fn (LoadPackage $package) => $this->renderAdminPartial('admin.load-packages.partials.name', compact('package')))
            ->editColumn('price_inc_vat_pence', fn (LoadPackage $package) => $this->renderAdminPartial('admin.load-packages.partials.price', compact('package')))
            ->addColumn('capacity', fn (LoadPackage $package) => $this->renderAdminPartial('admin.load-packages.partials.capacity', compact('package')))
            ->editColumn('pricing_status', fn (LoadPackage $package) => $this->renderAdminPartial('admin.partials.pricing-status-badge', ['status' => $package->pricing_status]))
            ->editColumn('is_active', fn (LoadPackage $package) => $this->renderStatusBadge(
                $package->is_active ? 'Active' : 'Inactive',
                $package->is_active ? 'active' : 'muted',
            ))
            ->addColumn('actions', fn (LoadPackage $package) => $this->renderAdminPartial('admin.load-packages.partials.actions', compact('package')))
            ->rawColumns(['package', 'price_inc_vat_pence', 'capacity', 'pricing_status', 'is_active', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $package = new LoadPackage([
            'pricing_status' => 'placeholder',
            'is_popular' => false,
            'is_active' => true,
            'sort_order' => 0,
            'vat_rate_basis_points' => config('pricing.default_vat_basis_points', 2000),
        ]);

        return view('admin.load-packages.create', $this->formData($package));
    }

    public function store(StoreLoadPackageRequest $request)
    {
        LoadPackage::create($request->loadPackageAttributes());

        return $this->success(redirect()->route('admin.load-packages.index'), 'Load package created successfully.');
    }

    public function edit(LoadPackage $loadPackage)
    {
        return view('admin.load-packages.edit', $this->formData($loadPackage));
    }

    public function update(UpdateLoadPackageRequest $request, LoadPackage $loadPackage)
    {
        $loadPackage->update($request->loadPackageAttributes());

        return $this->success(redirect()->route('admin.load-packages.index'), 'Load package updated successfully.');
    }

    public function destroy(LoadPackage $loadPackage)
    {
        // Soft deleted so booking snapshots that reference the package keep resolving.
        $loadPackage->delete();

        return $this->success(redirect()->route('admin.load-packages.index'), 'Load package deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(LoadPackage $package): array
    {
        return [
            'package' => $package,
            'incVatPounds' => Money::toPounds($package->price_inc_vat_pence),
            'exVatPounds' => Money::toPounds($package->price_ex_vat_pence),
            'vatPercent' => Money::basisPointsToPercent($package->vat_rate_basis_points ?? config('pricing.default_vat_basis_points', 2000)),
        ];
    }
}
