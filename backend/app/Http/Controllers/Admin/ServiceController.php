<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsAdminDataTables;
use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Page;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    use BuildsAdminDataTables;
    use FlashesMessages;

    public function index()
    {
        return view('admin.services.index');
    }

    public function data()
    {
        return $this->adminDataTable(
            Service::query()
                ->with(['category:id,name', 'heroMedia:id,disk,path,mime_type,alt_text,original_name'])
                ->withCount('contentBlocks')
                ->select(['id', 'service_category_id', 'hero_media_id', 'slug', 'name', 'route_path', 'status', 'is_featured', 'is_bookable', 'sort_order', 'published_at'])
        )
            ->addColumn('preview', fn (Service $service) => $service->heroMedia
                ? $this->renderAdminPartial('admin.media.partials.preview', ['media' => $service->heroMedia])
                : '<span class="text-muted small">No hero</span>')
            ->addColumn('service', fn (Service $service) => $this->renderAdminPartial('admin.services.partials.name', compact('service')))
            ->addColumn('category', fn (Service $service) => $service->category?->name ?? 'Uncategorised')
            ->editColumn('status', fn (Service $service) => $this->renderStatusBadge(
                str($service->status)->headline(),
                $service->is_published ? 'active' : 'muted',
            ))
            ->addColumn('flags', fn (Service $service) => $this->renderAdminPartial('admin.services.partials.flags', compact('service')))
            ->editColumn('published_at', fn (Service $service) => $this->formatAdminDate($service->published_at, 'd M Y', 'Not published'))
            ->addColumn('actions', fn (Service $service) => $this->renderAdminPartial('admin.services.partials.actions', compact('service')))
            ->rawColumns(['preview', 'service', 'status', 'flags', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $service = new Service([
            'status' => 'draft',
            'is_featured' => false,
            'is_bookable' => true,
            'sort_order' => 0,
        ]);

        return view('admin.services.create', $this->formData($service));
    }

    public function store(StoreServiceRequest $request)
    {
        $service = DB::transaction(function () use ($request): Service {
            $data = $request->validated();
            $related = Arr::pull($data, 'related_service_ids') ?? [];
            $seo = $this->pullSeo($data);

            $service = Service::create($this->withPublishedAt($data, null));
            $service->relatedServices()->sync($this->relatedPivot($related));
            $this->syncPageSeo($service, $seo);

            return $service;
        });

        return $this->success(
            redirect()->route('admin.services.edit', $service),
            'Service created successfully.',
        );
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', $this->formData($service));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        DB::transaction(function () use ($request, $service): void {
            $data = $request->validated();
            $related = Arr::pull($data, 'related_service_ids') ?? [];
            $seo = $this->pullSeo($data);

            $service->update($this->withPublishedAt($data, $service));
            $service->relatedServices()->sync($this->relatedPivot($related));
            $this->syncPageSeo($service->refresh(), $seo);
        });

        return $this->success(
            redirect()->route('admin.services.index'),
            'Service updated successfully.',
        );
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return $this->success(
            redirect()->route('admin.services.index'),
            'Service deleted successfully.',
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(Service $service): array
    {
        return [
            'service' => $service,
            'categories' => ServiceCategory::query()->ordered()->get(['id', 'name']),
            'pages' => Page::query()->orderBy('title')->get(['id', 'title', 'route_path']),
            'availableServices' => Service::query()
                ->when($service->exists, fn ($query) => $query->whereKeyNot($service->getKey()))
                ->ordered()
                ->get(['id', 'name', 'status']),
            'selectedRelatedIds' => $service->exists
                ? $service->relatedServices()->pluck('services.id')->all()
                : [],
            'page' => $service->page,
        ];
    }

    /**
     * Publishing stamps `published_at` once; dropping back to draft clears it.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function withPublishedAt(array $data, ?Service $service): array
    {
        $status = $data['status'] ?? 'draft';

        if ($status === 'published') {
            $data['published_at'] = $service?->published_at ?? now();
        } elseif ($status === 'draft') {
            $data['published_at'] = null;
        } else {
            $data['published_at'] = $service?->published_at;
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, string|null>
     */
    private function pullSeo(array &$data): array
    {
        return [
            'meta_title' => Arr::pull($data, 'meta_title'),
            'meta_description' => Arr::pull($data, 'meta_description'),
        ];
    }

    /**
     * @param array<int, int|string> $ids
     * @return array<int, array<string, int>>
     */
    private function relatedPivot(array $ids): array
    {
        return collect($ids)
            ->unique()
            ->values()
            ->mapWithKeys(fn ($id, int $index): array => [(int) $id => ['sort_order' => $index + 1]])
            ->all();
    }

    /**
     * SEO lives on the linked page, so it is only written when a page is attached.
     *
     * @param array<string, string|null> $seo
     */
    private function syncPageSeo(Service $service, array $seo): void
    {
        $page = $service->page;

        if (! $page) {
            return;
        }

        $page->update([
            'meta_title' => $seo['meta_title'],
            'meta_description' => $seo['meta_description'],
        ]);
    }
}
