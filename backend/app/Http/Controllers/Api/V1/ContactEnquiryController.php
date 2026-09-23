<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreContactEnquiryRequest;
use App\Models\ContactEnquiry;
use App\Support\WebsiteNotifier;

class ContactEnquiryController extends Controller
{
    /**
     * Accepts a submission from the public contact form.
     *
     * Oversized bodies and honeypot hits never reach here; the public form middleware
     * handles both. The office alert is queued, so a mail problem never fails the submit.
     */
    public function store(StoreContactEnquiryRequest $request)
    {
        $enquiry = ContactEnquiry::create($request->enquiryAttributes());

        WebsiteNotifier::enquiryReceived($enquiry);

        return response()->json([
            'ok' => true,
            'message' => 'Thanks, your enquiry has been sent. We will be in touch shortly.',
            'reference' => $enquiry->id,
        ], 201);
    }
}
