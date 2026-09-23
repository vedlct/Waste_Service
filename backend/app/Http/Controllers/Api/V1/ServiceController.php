<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ServiceResource;
use App\Http\Resources\Api\V1\ServiceSummaryResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::query()
            ->published()
            ->with(['category:id,slug,name', 'heroMedia'])
            ->when($request->filled('category'), fn ($query) => $query->whereHas(
                'category',
                fn ($builder) => $builder->where('slug', $request->string('category')->toString()),
            ))
            ->when($request->boolean('featured'), fn ($query) => $query->where('is_featured', true))
            ->when($request->boolean('bookable'), fn ($query) => $query->where('is_bookable', true))
            ->ordered()
            ->get();

        return ServiceSummaryResource::collection($services);
    }

    public function show(string $slug)
    {
        $service = Service::query()
            ->published()
            ->with([
                'category:id,slug,name',
                'page:id,meta_title,meta_description',
                'heroMedia',
                'contentBlocks' => fn ($query) => $query->enabled()->ordered(),
                'contentBlocks.media',
                'contentBlocks.items' => fn ($query) => $query->enabled()->ordered(),
                'contentBlocks.items.media',
                'relatedServices' => fn ($query) => $query->published()->with('heroMedia'),
                'faqs' => fn ($query) => $query->active()->ordered(),
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return ServiceResource::make($service);
    }
}
