<?php

namespace App\Filament\Resources\ConferenceResource\Pages;

use App\Filament\Resources\ConferenceResource;
use Filament\Actions;
// use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditConference extends EditRecord
{
    protected static string $resource = ConferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
            // Action::make('edit')
            //     ->label('Edit Venue')
            //     ->url(route('filament.app.resources.venues.edit', ['record' => $this->record->venue_id])),
        ];
    }
}
