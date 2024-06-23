<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Filament\Resources\SpeakerResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use App\Models\Speaker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getFormSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->circular()
                    ->collection('avatars')
                    ->defaultImageUrl(fn(Speaker $record) => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->name)),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter_handle')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                ComponentsSection::make('Personal Information')
                    ->description('The following information is used for the speaker profile.')
                    ->columns(3)
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('avatar')
                            ->collection('avatars')
                            ->hiddenLabel()
                            ->circular()
                            ->defaultImageUrl(fn(Speaker $record) => 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=' . urlencode($record->name)),
                        Group::make()
                            ->columns(2)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('name'),
                                TextEntry::make('email'),
                                TextEntry::make('twitter_handle')
                                    ->label('Twitter')
                                    ->getStateUsing(fn($record) => '@' . $record->twitter_handle)
                                    ->url(fn(Speaker $record) => 'https://twitter.com/' . $record->twitter_handle),
                                TextEntry::make('has_spoken')
                                    ->label('Has Spoken Before')
                                    ->getStateUsing(fn($record) => $record->talks()->approved()->count() ? 'Yes' : 'No')
                                    ->badge()
                                    ->color(fn($state) => $state == 'Yes' ? 'success' : 'danger'),

                            ])
                    ]),
                ComponentsSection::make('Biography')
                    // ->description('The following information is used for the speaker profile.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('bio')
                            ->hiddenLabel()
                            ->columnSpan(3)
                            ->markdown()
                            ->prose()
                            ->copyable(),
                            // ->extraAttributes(['class' => 'prose lg:prose-xl']),
                    ]),
                ComponentsSection::make('Qualifications')
                    // ->description('The following information is used for the speaker profile.')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('qualifications')
                            ->hiddenLabel()
                            ->bulleted(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            // 'edit' => Pages\EditSpeaker::route('/{record}/edit'),
            'view' => Pages\ViewSpeaker::route('/{record}'),
        ];
    }
}
