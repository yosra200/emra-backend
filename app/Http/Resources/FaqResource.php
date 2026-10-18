<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->localised('question'),
            'question_ar' => $this->getTranslation('question', 'ar', false),
            'question_en' => $this->getTranslation('question', 'en', false),
            'answer' => $this->localised('answer'),
            'answer_ar' => $this->getTranslation('answer', 'ar', false),
            'answer_en' => $this->getTranslation('answer', 'en', false),
        ];
    }

    /**
     * The translation for the current locale, falling back to the other language
     * when it was left empty in the admin panel.
     */
    private function localised(string $attribute): string
    {
        $ar = $this->getTranslation($attribute, 'ar', false);
        $en = $this->getTranslation($attribute, 'en', false);

        return app()->getLocale() === 'en' ? ($en ?: $ar) : ($ar ?: $en);
    }
}
