<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

/**
 * Base for the panel pages that edit a handful of rows in the `settings` table.
 *
 * A child page only has to say which keys it owns and how they are laid out;
 * loading, saving and the save button are handled here.
 *
 * @property-read Schema $form
 */
abstract class SettingsPage extends Page
{
    protected string $view = 'filament.pages.settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * The settings keys this page owns.
     *
     * @return array<int, string>
     */
    abstract protected function settingsKeys(): array;

    public function mount(): void
    {
        $this->form->fill(Setting::getMany($this->settingsKeys()));
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
                                ->label(__('panel.settings.actions.save'))
                                ->submit('save'),
                        ])->key('form-actions'),
                    ]),
            ]);
    }

    public function save(): void
    {
        Setting::setMany($this->form->getState());

        Notification::make()
            ->title(__('panel.settings.saved'))
            ->success()
            ->send();
    }
}
