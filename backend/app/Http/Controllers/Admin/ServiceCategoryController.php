<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Admin\Concerns\GuardsDeletions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceCategoryRequest;
use App\Http\Requests\Admin\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;
    use GuardsDeletions;

    public function index()
    {
        return view('admin.service-categories.index');
    }

    public function data()
    {
        return $this->adminDataTable(
            ServiceCategory::query()
                ->with('parent:id,name')
                ->withCount(['children', 'services'])
                ->select(['id', 'parent_id', 'slug', 'name', 'icon', 'is_active', 'sort_order', 'created_at'])
        )
            ->addColumn('category', fn (ServiceCategory $category) => $this->renderAdminPartial('admin.service-categories.partials.name', compact('category')))
            ->addColumn('parent', fn (ServiceCategory $category) => $category->parent?->name ?? 'Top level')
            ->editColumn('is_active', fn (ServiceCategory $category) => $this->renderStatusBadge(
                $category->is_active ? 'Active' : 'Inactive',
                $category->is_active ? 'active' : 'muted',
            ))
            ->addColumn('usage', fn (ServiceCategory $category) => $this->renderStatusBadge(
                $category->services_count.' services, '.$category->children_count.' sub',
                $category->services_count || $category->children_count ? 'role' : 'muted',
            ))
            ->editColumn('created_at', fn (ServiceCategory $category) => $this->formatAdminDate($category->created_at, 'd M Y'))
            ->addColumn('actions', fn (ServiceCategory $category) => $this->renderAdminPartial('admin.service-categories.partials.actions', compact('category')))
            ->rawColumns(['category', 'is_active', 'usage', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $category = new ServiceCategory(['is_active' => true, 'sort_order' => 0]);

        return view('admin.service-categories.create', [
            'category' => $category,
            'parents' => ServiceCategory::parentOptions(),
        ]);
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        ServiceCategory::create($request->validated());

        return $this->success(redirect()->route('admin.service-categories.index'), 'Service category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service-categories.edit', [
            'category' => $serviceCategory,
            'parents' => ServiceCategory::parentOptions($serviceCategory),
        ]);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        $serviceCategory->update($request->validated());

        return $this->success(redirect()->route('admin.service-categories.index'), 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $blocked = $this->deletionBlockedMessage('This category', [
            'Services' => $serviceCategory->services()->count(),
            'Sub categories' => $serviceCategory->children()->count(),
        ]);

        if ($blocked !== null) {
            return $this->error(back(), $blocked);
        }

        $serviceCategory->delete();

        return $this->success(redirect()->route('admin.service-categories.index'), 'Service category deleted successfully.');
    }
}
