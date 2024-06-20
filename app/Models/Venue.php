<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'region' => Region::class,
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(60),
            Forms\Components\TextInput::make('city')
                ->required()
                ->maxLength(60),
            Forms\Components\Select::make('region')
                ->enum(Region::class)
                ->required()
                ->options(Region::class),
            Forms\Components\TextInput::make('country')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('postal_code')
                ->required()
                ->maxLength(255),
        ];
    }
}
