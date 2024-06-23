<?php

namespace App\Filament\Resources;

use App\Enums\TalkLength;
use App\Enums\TalkStatus;
use App\Filament\Resources\TalkResource\Pages;
use App\Filament\Resources\TalkResource\RelationManagers;
use App\Models\Speaker;
use App\Models\Talk;
use Barryvdh\Debugbar\Facades\Debugbar;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->filtersTriggerAction(function($action) {
                return $action->button()->label('Filters');
            })
            ->columns([
                SpatieMediaLibraryImageColumn::make('speaker.avatar')
                    ->label('Speaker Avatar')
                    ->circular()
                    ->collection('avatars')
                    ->defaultImageUrl(fn (Talk $record) => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->speaker->name)),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->description(fn(Talk $record) => Str::of($record->abstract)->limit(50))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('length')
                    ->icon(fn($state) => $state->getIcon())
                    ->color(fn($state) => $state->getColor()),
                Tables\Columns\ToggleColumn::make('is_newtalk'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Talk Status')
                    ->badge()
                    ->sortable()
                    ->color(fn($state) => $state->getColor()),
            ])
            ->filters([
                SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('is_newtalk'),
                Tables\Filters\Filter::make('has_avatar')
                    ->label('Show only speakers with avatar')
                    ->query(fn ($query)
                        => $query->whereHas('speaker.media', fn(Builder $query) => $query->whereNotNull('file_name')))
                        ->toggle(),
            ], layout: FiltersLayout::Dropdown)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->visible(fn(Talk $record) => $record->status !== TalkStatus::APPROVED)
                        ->icon('heroicon-o-check-circle')
                        ->iconSize(IconSize::Medium)
                        ->label('Approve talk')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->status = TalkStatus::APPROVED;
                            $record->save();
                            // maybe email the speaker, telling them that the talk has been approved
                        })
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Talk approved!')
                                ->body('The talk has been approved and added to the conference schedule!')
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->visible(fn (Talk $record) => $record->status !== TalkStatus::REJECTED)
                        ->label('Reject talk')
                        ->icon('heroicon-o-no-symbol')
                        ->iconSize(IconSize::Medium)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Talk $record) {
                            $record->status = TalkStatus::REJECTED;
                            $record->save();
                            // maybe email the speaker, telling them that the talk has been rejected
                        })
                        ->after(function () {
                            Notification::make()
                                ->danger()
                                ->title('Talk rejected!')
                                ->body('The talk has been rejected and removed from the conference schedule!')
                                ->send();
                        }),
                    ]),
                ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve selected')
                        ->icon('heroicon-o-check-circle')
                        ->iconSize(IconSize::Medium)
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(fn ($record) => $record->update(['status' => TalkStatus::APPROVED]));
                            // Talk::query()
                            //     ->whereIn('id', $records->pluck('id'))
                            //     ->update(['status' => TalkStatus::APPROVED]);
                        }),
                // ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->color('success')
                    ->action(function ($livewire) {
                        Notification::make()
                            ->duration(3)
                            ->info()
                            ->title('Exporting talks...')
                            ->body('This action is not yet implemented!')
                            ->send();
                        // $livewire->getFilteredTableQuery()->get()->debug();
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            // 'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
