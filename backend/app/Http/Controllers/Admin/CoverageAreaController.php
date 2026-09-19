<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoverageAreaRequest;
use App\Http\Requests\Admin\UpdateCoverageAreaRequest;
use App\Models\CoverageArea;
use App\Models\CoverageRegion;
use Illuminate\Http\Request;

class CoverageAreaController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.coverage-areas.index', [
            'regions' => CoverageRegion::query()->ordered()->get(['id', 'name']),
            'missingPostcodeCount' => CoverageArea::query()->whereNull('postcode_prefix')->count(),
        ]);
    }

    public function data(Request $request)
    {
        $query = CoverageArea::query()
            ->with('region:id,name')
            ->select(['id', 'coverage_region_id', 'slug', 'name', 'postcode_prefix', 'latitude', 'longitude', 'is_featured', 'is_active', 'sort_order'])
            ->when($request->filled('coverage_region_id'), fn ($builder) => $builder->where('coverage_region_id', $request->integer('coverage_region_id')))
            ->when($request->input('featured') === '1', fn ($builder) => $builder->where('is_featured', true))
            ->when($request->input('missing_postcode') === '1', fn ($builder) => $builder->whereNull('postcode_prefix'));

        return $this->adminDataTable($query)
            ->addColumn('area', fn (CoverageArea $area) => $this->renderAdminPartial('admin.coverage-areas.partials.name', compact('area')))
            ->addColumn('region', fn (CoverageArea $area) => $area->region?->name ?? 'Unassigned')
            ->editColumn('postcode_prefix', fn (CoverageArea $area) => $area->postcode_prefix
                ? $this->renderStatusBadge($area->postcode_prefix, 'role')
                : '<span class="text-muted small">Not set</span>')
            ->addColumn('coordinates', fn (CoverageArea $area) => $this->renderAdminPartial('admin.coverage-areas.partials.coordinates', compact('area')))
            ->editColumn('is_featured', fn (CoverageArea $area) => $this->renderStatusBadge(
                $area->is_featured ? 'Featured' : 'Standard',
                $area->is_featured ? 'role' : 'muted',
            ))
            ->editColumn('is_active', fn (CoverageArea $area) => $this->renderStatusBadge(
                $area->is_active ? 'Active' : 'Inactive',
                $area->is_active ? 'active' : 'muted',
            ))
            ->addColumn('actions', fn (CoverageArea $area) => $this->renderAdminPartial('admin.coverage-areas.partials.actions', compact('area')))
            ->rawColumns(['area', 'postcode_prefix', 'coordinates', 'is_featured', 'is_active', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $area = new CoverageArea([
            'coverage_region_id' => request()->integer('region') ?: null,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        return view('admin.coverage-areas.create', $this->formData($area));
    }

    public function store(StoreCoverageAreaRequest $request)
    {
        CoverageArea::create($request->validated());

        return $this->success(redirect()->route('admin.coverage-areas.index'), 'Coverage area created successfully.');
    }

    public function edit(CoverageArea $coverageArea)
    {
        return view('admin.coverage-areas.edit', $this->formData($coverageArea));
    }

    public function update(UpdateCoverageAreaRequest $request, CoverageArea $coverageArea)
    {
        $coverageArea->update($request->validated());

        return $this->success(redirect()->route('admin.coverage-areas.index'), 'Coverage area updated successfully.');
    }

    public function destroy(CoverageArea $coverageArea)
    {
        $coverageArea->delete();

        return $this->success(redirect()->route('admin.coverage-areas.index'), 'Coverage area deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(CoverageArea $area): array
    {
        return [
            'area' => $area,
            'regions' => CoverageRegion::query()->ordered()->get(['id', 'name']),
        ];
    }
}
