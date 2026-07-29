<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('الاسم الاول')
                    ->searchable(),
                TextColumn::make('last_name')
                    ->label('الاسم الاخير')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('رقم الهاتف')
                    ->searchable(),
                TextColumn::make('second_phone')
                    ->label('رقم الهاتف الثاني')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->searchable(),
                TextColumn::make('governorate')
                    ->label('المحافظة')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('عنوان البريد الإلكتروني')
                    ->searchable(),
                IconColumn::make('is_admin')
                    ->label('هل هو مسؤول؟')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
