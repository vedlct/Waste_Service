<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceContentBlockRequest;
use App\Http\Requests\Admin\UpdateServiceContentBlockRequest;
use App\Models\Service;
use App\Models\ServiceContentBlock;

class ServiceContentBlockController extends Controller
{
    use FlashesMessages;

    public function index(Service $service)
    {
        return view('admin.services.blocks.index', [
            'service' => $service,
            'blocks' => $service->contentBlocks()->with('media:id,disk,path,mime_type,alt_text,original_name')->withCount('items')->get(),
        ]);
    }

    public function create(Service $service)
    {
        $block = new ServiceContentBlock([
            'component' => config('service_cms.default_block_component', 'content_block'),
            'is_enabled' => true,
            'sort_order' => ($service->contentBlocks()->max('sort_order') ?? 0) + 1,
        ]);

        return view('admin.services.blocks.create', compact('service', 'block'));
    }

    public function store(StoreServiceContentBlockRequest $request, Service $service)
    {
        $block = $service->contentBlocks()->create($request->validated());

        return $this->success(
            redirect()->route('admin.services.blocks.edit', [$service, $block]),
            'Content block created successfully.',
        );
    }

    public function edit(Service $service, ServiceContentBlock $block)
    {
        $this->ensureBlockBelongsToService($service, $block);

        return view('admin.services.blocks.edit', [
            'service' => $service,
            'block' => $block,
            'items' => $block->items()->with('media:id,disk,path,mime_type,alt_text,original_name')->get(),
        ]);
    }

    public function update(UpdateServiceContentBlockRequest $request, Service $service, ServiceContentBlock $block)
    {
        $this->ensureBlockBelongsToService($service, $block);

        $block->update($request->validated());

        return $this->success(
            redirect()->route('admin.services.blocks.index', $service),
            'Content block updated successfully.',
        );
    }

    public function destroy(Service $service, ServiceContentBlock $block)
    {
        $this->ensureBlockBelongsToService($service, $block);

        // Block items cascade with the block, so the delete is always allowed.
        $block->delete();

        return $this->success(
            redirect()->route('admin.services.blocks.index', $service),
            'Content block deleted successfully.',
        );
    }

    private function ensureBlockBelongsToService(Service $service, ServiceContentBlock $block): void
    {
        abort_unless((int) $block->service_id === (int) $service->id, 404);
    }
}
