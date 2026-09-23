<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PriceCategoryResource;
use App\Http\Resources\Api\V1\ServiceItemResource;
use App\Models\PriceCategory;
use App\Models\ServiceItem;
use Illuminate\Http\Request;

class PriceCategoryController extends Controller
{
    /**
     * Pass `with_items=1` to embed each category's active items, so the booking page can
     * load the whole catalogue in one request.
     */
    public function index(Request $request)
    {
        $categories = PriceCategory::query()
            ->active()
            ->with('image')
            ->withCount(['items' => fn ($query) => $query->where('is_active', true)])
            ->when($request->boolean('with_items'), fn ($query) => $query->with([
                'items' => fn ($builder) => $builder->active()->ordered()->with('image'),
            ]))
            ->ordered()
            ->get();

        return PriceCategoryResource::collection($categories);
    }

    public function items(string $slug)
    {
        $category = PriceCategory::query()->active()->where('slug', $slug)->firstOrFail();

        $items = ServiceItem::query()
            ->active()
            ->where('price_category_id', $category->id)
            ->with('image')
            ->ordered()
            ->get();

        return ServiceItemResource::collection($items);
    }
}
