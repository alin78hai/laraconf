<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
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
            Forms\Components\Section::make('Conference Details')
                // ->description('Conference Details')
                // ->icon('heroicon-o-information-circle')
                ->collapsible(true)
                ->columns(3)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Conference Name')
                        ->columnSpan(2)
                        ->required()
                        ->maxLength(60),
                    Forms\Components\Select::make('status')
                        ->required()
                        ->options([
                            'upcoming' => 'Upcoming',
                            'ongoing' => 'Ongoing',
                            'past' => 'Past',
                        ]),
                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull()
                        // ->disableToolbarButtons(['code', 'quote', 'italic', 'strike', 'subscript', 'superscript'])
                        ->required(),
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
                ]),
            Forms\Components\Section::make('Location')
                ->collapsible()
                ->schema([
                    Forms\Components\Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::asSelectArray())
                        ->required(),
                    Forms\Components\Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Venue::getFormSchema())
                        ->editOptionForm(Venue::getFormSchema())
                        ->relationship(name: 'venue', titleAttribute: 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                            $query->where('region', $get('region'));
                        }),
                ]),

            Section::make('Speakers')
                ->collapsible()
                ->schema([
                    Forms\Components\CheckboxList::make('speakers')
                        ->hiddenLabel(true)
                        ->columnSpanFull()
                        ->relationship('speakers', 'name')
                        ->options(Speaker::all()->pluck('name', 'id'))
                        // ->descriptions(Speaker::all()->pluck('bio', 'id'))
                        ->columns(3)
                        ->searchable(),
                ]),
        ];
    }
}
