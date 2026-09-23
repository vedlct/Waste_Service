<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Without a filter this returns the general FAQs shown on the FAQ page. Pass
     * `service=<slug>` for the questions attached to one service.
     */
    public function index(Request $request)
    {
        $faqs = Faq::query()
            ->active()
            ->when(
                $request->filled('service'),
                fn ($query) => $query->whereHas(
                    'service',
                    fn ($builder) => $builder->where('slug', $request->string('service')->toString()),
                )->with('service:id,slug,name'),
                fn ($query) => $query->general(),
            )
            ->ordered()
            ->get();

        return FaqResource::collection($faqs);
    }
}
