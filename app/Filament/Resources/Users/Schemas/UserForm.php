<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->label('الاسم الاول')
                    ->required(),
                TextInput::make('last_name')
                    ->label('الاسم الاخير')
                    ->required(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required(),
                TextInput::make('second_phone')
                    ->label('رقم الهاتف الثاني')
                    ->tel()
                    ->default(null),
                TextInput::make('address')
                    ->label('العنوان')
                    ->default(null),
                TextInput::make('governorate')
                    ->label('المحافظة')
                    ->default(null),
                TextInput::make('email')
                    ->label('عنوان البريد الإلكتروني')
                    ->email()
                    ->default(null),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(),
                Toggle::make('is_admin')
                    ->label('هل هو مسؤول؟')
                    ->required(),
            ]);
    }
}
