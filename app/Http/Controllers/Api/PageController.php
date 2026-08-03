<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\Setting;
use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

class PageController extends Controller
{
    use ApiResponse;

    #[OA\Get(
        path: '/api/faqs',
        summary: 'Get FAQs',
        description: 'Retrieve the frequently asked questions managed from the admin panel. Each entry carries the text of the current app locale plus both translations.',
        tags: ['Pages'],
        responses: [
            new OA\Response(response: 200, description: 'FAQs retrieved successfully')
        ]
    )]
    public function faqs()
    {
        $faqs = Faq::orderBy('id')->get();

        return $this->successResponse(FaqResource::collection($faqs), __('messages.faqs_retrieved'));
    }

    #[OA\Get(
        path: '/api/about-us',
        summary: 'Get About Us',
        description: 'Retrieve the About us text managed from the admin panel, in the current app locale plus both translations.',
        tags: ['Pages'],
        responses: [
            new OA\Response(response: 200, description: 'About us retrieved successfully')
        ]
    )]
    public function aboutUs()
    {
        $about = Setting::getMany(['about_us_ar', 'about_us_en']);

        $current = app()->getLocale() === 'en'
            ? ($about['about_us_en'] ?: $about['about_us_ar'])
            : ($about['about_us_ar'] ?: $about['about_us_en']);

        return $this->successResponse([
            'about_us' => $current,
            'about_us_ar' => $about['about_us_ar'],
            'about_us_en' => $about['about_us_en'],
        ], __('messages.about_us_retrieved'));
    }
}
