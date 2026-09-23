<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePageRequest;
use App\Models\Page;
use App\Models\SiteSetting;

class PageController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    /**
     * Seeded placeholder copy that should be replaced before launch.
     */
    public const PLACEHOLDER_DESCRIPTION = 'MR. TEE Removals website page.';

    public function index()
    {
        return view('admin.pages.index', [
            'placeholderCount' => Page::query()
                ->where(fn ($query) => $query->whereNull('meta_description')
                    ->orWhere('meta_description', '')
                    ->orWhere('meta_description', self::PLACEHOLDER_DESCRIPTION))
                ->where('is_indexable', true)
                ->count(),
        ]);
    }

    public function data()
    {
        return $this->adminDataTable(
            Page::query()
                ->with('service:id,page_id,name')
                ->select(['id', 'slug', 'route_path', 'title', 'template', 'meta_title', 'meta_description', 'is_indexable', 'sort_order', 'updated_at'])
        )
            ->addColumn('page', fn (Page $page) => $this->renderAdminPartial('admin.pages.partials.name', compact('page')))
            ->addColumn('seo', fn (Page $page) => $this->renderAdminPartial('admin.pages.partials.seo', compact('page')))
            ->editColumn('is_indexable', fn (Page $page) => $this->renderStatusBadge(
                $page->is_indexable ? 'Indexed' : 'Hidden from search',
                $page->is_indexable ? 'active' : 'muted',
            ))
            ->editColumn('updated_at', fn (Page $page) => $this->formatAdminDate($page->updated_at, 'd M Y'))
            ->addColumn('actions', fn (Page $page) => '<a class="btn btn-sm btn-outline-tee" href="'.e(route('admin.pages.edit', $page)).'" title="Edit SEO"><i class="bi bi-pencil-square"></i></a>')
            ->rawColumns(['page', 'seo', 'is_indexable', 'actions'])
            ->toJson();
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', [
            'page' => $page->load(['service:id,page_id,name', 'ogImage']),
            'siteUrl' => rtrim((string) config('app.frontend_url'), '/'),
            'defaultDescription' => (string) SiteSetting::valueOf('seo', 'default_meta_description', ''),
        ]);
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->update($request->validated() + ['updated_by' => $request->user()->id]);

        return $this->success(redirect()->route('admin.pages.index'), 'Page updated successfully.');
    }
}
