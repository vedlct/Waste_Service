<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\FlashesMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceBlockItemRequest;
use App\Http\Requests\Admin\UpdateServiceBlockItemRequest;
use App\Models\Service;
use App\Models\ServiceBlockItem;
use App\Models\ServiceContentBlock;

class ServiceBlockItemController extends Controller
{
    use FlashesMessages;

    public function create(Service $service, ServiceContentBlock $block)
    {
        $this->ensureBlockBelongsToService($service, $block);

        $item = new ServiceBlockItem([
            'is_enabled' => true,
            'sort_order' => ($block->items()->max('sort_order') ?? 0) + 1,
        ]);

        return view('admin.services.blocks.items.create', compact('service', 'block', 'item'));
    }

    public function store(StoreServiceBlockItemRequest $request, Service $service, ServiceContentBlock $block)
    {
        $this->ensureBlockBelongsToService($service, $block);

        $block->items()->create($request->validated());

        return $this->success(
            redirect()->route('admin.services.blocks.edit', [$service, $block]),
            'Block item created successfully.',
        );
    }

    public function edit(Service $service, ServiceContentBlock $block, ServiceBlockItem $item)
    {
        $this->ensureItemBelongsToBlock($service, $block, $item);

        return view('admin.services.blocks.items.edit', compact('service', 'block', 'item'));
    }

    public function update(UpdateServiceBlockItemRequest $request, Service $service, ServiceContentBlock $block, ServiceBlockItem $item)
    {
        $this->ensureItemBelongsToBlock($service, $block, $item);

        $item->update($request->validated());

        return $this->success(
            redirect()->route('admin.services.blocks.edit', [$service, $block]),
            'Block item updated successfully.',
        );
    }

    public function destroy(Service $service, ServiceContentBlock $block, ServiceBlockItem $item)
    {
        $this->ensureItemBelongsToBlock($service, $block, $item);

        $item->delete();

        return $this->success(
            redirect()->route('admin.services.blocks.edit', [$service, $block]),
            'Block item deleted successfully.',
        );
    }

    private function ensureBlockBelongsToService(Service $service, ServiceContentBlock $block): void
    {
        abort_unless((int) $block->service_id === (int) $service->id, 404);
    }

    private function ensureItemBelongsToBlock(Service $service, ServiceContentBlock $block, ServiceBlockItem $item): void
    {
        $this->ensureBlockBelongsToService($service, $block);

        abort_unless((int) $item->service_content_block_id === (int) $block->id, 404);
    }
}
