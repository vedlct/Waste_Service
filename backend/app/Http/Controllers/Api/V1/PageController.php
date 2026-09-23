<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Every published page with its route and SEO fields, without sections. The frontend
     * uses it for each page's title and description, and for the sitemap.
     */
    public function index()
    {
        return PageResource::collection(
            Page::query()->published()->with('ogImage')->orderBy('sort_order')->orderBy('id')->get(),
        );
    }

    public function show(string $slug)
    {
        $page = Page::query()
            ->published()
            ->with([
                'heroMedia',
                'ogImage',
                'sections' => fn ($query) => $query->enabled()->ordered(),
                'sections.media',
                'sections.items' => fn ($query) => $query->enabled(),
                'sections.items.media',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return PageResource::make($page);
    }
}
