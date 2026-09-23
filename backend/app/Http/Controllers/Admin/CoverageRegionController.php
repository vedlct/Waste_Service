<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Admin\Concerns\GuardsDeletions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoverageRegionRequest;
use App\Http\Requests\Admin\UpdateCoverageRegionRequest;
use App\Models\CoverageRegion;

class CoverageRegionController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;
    use GuardsDeletions;

    public function index()
    {
        return view('admin.coverage-regions.index');
    }

    public function data()
    {
        return $this->adminDataTable(
            CoverageRegion::query()
                ->withCount([
                    'areas',
                    'areas as featured_areas_count' => fn ($query) => $query->where('is_featured', true),
                ])
                ->select(['id', 'slug', 'name', 'description', 'is_active', 'sort_order'])
        )
            ->addColumn('region', fn (CoverageRegion $region) => $this->renderAdminPartial('admin.coverage-regions.partials.name', compact('region')))
            ->editColumn('is_active', fn (CoverageRegion $region) => $this->renderStatusBadge(
                $region->is_active ? 'Active' : 'Inactive',
                $region->is_active ? 'active' : 'muted',
            ))
            ->addColumn('areas', fn (CoverageRegion $region) => $this->renderAdminPartial('admin.coverage-regions.partials.areas', compact('region')))
            ->addColumn('actions', fn (CoverageRegion $region) => $this->renderAdminPartial('admin.coverage-regions.partials.actions', compact('region')))
            ->rawColumns(['region', 'is_active', 'areas', 'actions'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.coverage-regions.create', [
            'region' => new CoverageRegion(['is_active' => true, 'sort_order' => 0]),
        ]);
    }

    public function store(StoreCoverageRegionRequest $request)
    {
        CoverageRegion::create($request->validated());

        return $this->success(redirect()->route('admin.coverage-regions.index'), 'Coverage region created successfully.');
    }

    public function edit(CoverageRegion $coverageRegion)
    {
        return view('admin.coverage-regions.edit', ['region' => $coverageRegion]);
    }

    public function update(UpdateCoverageRegionRequest $request, CoverageRegion $coverageRegion)
    {
        $coverageRegion->update($request->validated());

        return $this->success(redirect()->route('admin.coverage-regions.index'), 'Coverage region updated successfully.');
    }

    public function destroy(CoverageRegion $coverageRegion)
    {
        // coverage_areas cascades on delete, so a region with areas is never removed silently.
        $blocked = $this->deletionBlockedMessage('This region', [
            'Coverage areas' => $coverageRegion->areas()->count(),
        ]);

        if ($blocked !== null) {
            return $this->error(back(), $blocked);
        }

        $coverageRegion->delete();

        return $this->success(redirect()->route('admin.coverage-regions.index'), 'Coverage region deleted successfully.');
    }
}
