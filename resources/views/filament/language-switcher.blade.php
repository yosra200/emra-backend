@php
    use App\Http\Middleware\SetPanelLocale;

    $current = app()->getLocale();
@endphp

<div class="fi-dropdown-list flex items-center gap-x-1 px-2">
    @foreach (SetPanelLocale::SUPPORTED as $locale)
        <a
            href="{{ route('filament.locale.switch', $locale) }}"
            @class([
                'rounded-lg px-2 py-1 text-sm font-medium transition',
                'bg-primary-600 text-white' => $locale === $current,
                'text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5' => $locale !== $current,
            ])
        >
            {{ __("panel.language.{$locale}") }}
        </a>
    @endforeach
</div>
