<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class ManageAbout extends Page
{
    /**
     * The settings keys this page owns.
     *
     * @var array<int, string>
     */
    private const KEYS = ['about_us_ar', 'about_us_en'];

    protected string $view = 'filament.pages.manage-about';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('panel.about.title');
    }

    public function getTitle(): string
    {
        return __('panel.about.title');
    }

    public function mount(): void
    {
        $this->form->fill(Setting::getMany(self::KEYS));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('panel.about.section'))
                    ->description(__('panel.about.hint'))
                    ->schema([
                        Tabs::make('translations')
                            ->tabs([
                                Tabs\Tab::make(__('panel.language.ar'))
                                    ->schema([
                                        Textarea::make('about_us_ar')
                                            ->label(__('panel.about.fields.about_us_ar'))
                                            ->rows(12),
                                    ]),

                                Tabs\Tab::make(__('panel.language.en'))
                                    ->schema([
                                        Textarea::make('about_us_en')
                                            ->label(__('panel.about.fields.about_us_en'))
                                            ->rows(12),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('panel.about.actions.save'))
                                ->submit('save'),
                        ])->key('form-actions'),
                    ]),
            ]);
    }

    public function save(): void
    {
        Setting::setMany($this->form->getState());

        Notification::make()
            ->title(__('panel.about.saved'))
            ->success()
            ->send();
    }
}
