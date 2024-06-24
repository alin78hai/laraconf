<?php

namespace App\Filament\Resources\SpeakerResource\Pages;

use App\Filament\Resources\SpeakerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;

class ListSpeakers extends ListRecords
{
    protected static string $resource = SpeakerResource::class;

    // protected function paginateTableQuery(Builder $query): CursorPaginator
    // {
    //     return $query->cursorPaginate($this->getTableRecordsPerPage());
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
