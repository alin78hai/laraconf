<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'region' => Region::class,
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Conference Name')
                ->required()
                ->maxLength(60),
            Forms\Components\MarkdownEditor::make('description')
                ->required()
                // ->disableToolbarButtons(['code', 'quote', 'italic', 'strike', 'subscript', 'superscript'])
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('start_date')
                ->native(false)
                ->firstDayOfWeek(1)
                ->displayFormat('l, j-M-Y h:i A')
                ->suffixIcon('heroicon-o-calendar')
                ->required(),
            Forms\Components\DateTimePicker::make('end_date')
                ->native(false)
                ->firstDayOfWeek(1)
                ->displayFormat('l, j-M-Y h:i A')
                ->suffixIcon('heroicon-o-calendar')
                ->required(),
            Forms\Components\Select::make('region')
                ->live()
                ->enum(Region::class)
                ->options(Region::class)
                ->required(),
            Forms\Components\Select::make('venue_id')
                ->searchable()
                ->preload()
                ->createOptionForm(Venue::getFormSchema())
                ->editOptionForm(Venue::getFormSchema())
                ->relationship(name: 'venue', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                    $query->where('region', $get('region'));
                }),
            Forms\Components\Select::make('status')
                ->required()
                ->options([
                    'upcoming' => 'Upcoming',
                    'ongoing' => 'Ongoing',
                    'past' => 'Past',
                ]),
        ];
    }
}
