<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

class ContactUsController extends Controller
{
    use ApiResponse;

    #[OA\Post(
        path: '/api/contact-us',
        summary: 'Send Contact Message',
        description: 'Store a message sent from the contact form. Open to guests and signed-in customers alike; the message then shows up in the admin panel.',
        tags: ['Contact us'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'phone', 'message'],
                properties: [
                    new OA\Property(property: 'name', description: 'Sender name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'phone', description: 'Sender phone number', type: 'string', maxLength: 20),
                    new OA\Property(property: 'email', description: 'Sender email, optional', type: 'string', format: 'email', nullable: true),
                    new OA\Property(property: 'message', description: 'Message body', type: 'string'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Your message has been sent successfully'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function store(ContactUsRequest $request)
    {
        ContactUs::create($request->validated());

        return $this->successMessage(__('messages.contact_message_sent'));
    }
}
