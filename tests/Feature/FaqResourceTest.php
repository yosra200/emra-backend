<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageAbout;
use App\Filament\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Resources\Faqs\Pages\EditFaq;
use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FaqResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_the_faq_list_renders(): void
    {
        Faq::create([
            'question' => ['ar' => 'إزاي أتابع طلبي؟', 'en' => 'How do I track my order?'],
            'answer' => ['ar' => 'من صفحة طلباتي.', 'en' => 'From the orders page.'],
        ]);

        Livewire::test(ListFaqs::class)->assertSuccessful();
    }

    public function test_a_faq_can_be_created_in_both_languages(): void
    {
        Livewire::test(CreateFaq::class)
            ->fillForm([
                'question.ar' => 'هل الشحن مجاني؟',
                'question.en' => 'Is shipping free?',
                'answer.ar' => 'الشحن مجاني للطلبات فوق ٥٠٠ جنيه.',
                'answer.en' => 'Shipping is free over EGP 500.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $faq = Faq::firstOrFail();

        $this->assertSame('هل الشحن مجاني؟', $faq->getTranslation('question', 'ar'));
        $this->assertSame('Is shipping free?', $faq->getTranslation('question', 'en'));
        $this->assertSame('الشحن مجاني للطلبات فوق ٥٠٠ جنيه.', $faq->getTranslation('answer', 'ar'));
        $this->assertSame('Shipping is free over EGP 500.', $faq->getTranslation('answer', 'en'));
    }

    public function test_the_arabic_question_is_required(): void
    {
        Livewire::test(CreateFaq::class)
            ->fillForm([
                'question.ar' => '',
                'answer.ar' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['question.ar', 'answer.ar']);
    }

    public function test_the_edit_form_is_filled_with_both_translations_and_can_be_updated(): void
    {
        $faq = Faq::create([
            'question' => ['ar' => 'سؤال قديم', 'en' => 'Old question'],
            'answer' => ['ar' => 'إجابة قديمة', 'en' => 'Old answer'],
        ]);

        Livewire::test(EditFaq::class, ['record' => $faq->getRouteKey()])
            ->assertSuccessful()
            ->assertSchemaStateSet([
                'question.ar' => 'سؤال قديم',
                'question.en' => 'Old question',
                'answer.ar' => 'إجابة قديمة',
                'answer.en' => 'Old answer',
            ])
            ->fillForm([
                'question.ar' => 'سؤال جديد',
                'answer.ar' => 'إجابة جديدة',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $faq->refresh();

        $this->assertSame('سؤال جديد', $faq->getTranslation('question', 'ar'));
        $this->assertSame('Old question', $faq->getTranslation('question', 'en'));
        $this->assertSame('إجابة جديدة', $faq->getTranslation('answer', 'ar'));
    }

    public function test_the_about_us_page_saves_into_settings(): void
    {
        Livewire::test(ManageAbout::class)
            ->assertSuccessful()
            ->fillForm([
                'about_us_ar' => 'إمرأة براند مصري للطرح.',
                'about_us_en' => 'Emra is an Egyptian scarf brand.',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('إمرأة براند مصري للطرح.', Setting::get('about_us_ar'));
        $this->assertSame('Emra is an Egyptian scarf brand.', Setting::get('about_us_en'));
    }

    public function test_the_about_us_page_reloads_what_was_saved(): void
    {
        Setting::set('about_us_ar', 'نص محفوظ');
        Setting::set('about_us_en', 'Stored text');

        Livewire::test(ManageAbout::class)
            ->assertSchemaStateSet([
                'about_us_ar' => 'نص محفوظ',
                'about_us_en' => 'Stored text',
            ]);
    }
}
