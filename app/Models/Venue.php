<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

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
            SpatieMediaLibraryFileUpload::make('images')
                ->collection('images')
                ->multiple()
                ->maxFiles(5)
                ->maxSize(10 * 1024 * 1024)
                ->directory(directory: 'venues')
                ->imageEditor(),
            Forms\Components\TextInput::make('city')
                ->required()
                ->maxLength(60),
            Forms\Components\Select::make('region')
                ->enum(Region::class)
                ->options(Region::asSelectArray())
                ->required(),
            Forms\Components\TextInput::make('country')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('postal_code')
                ->required()
                ->maxLength(255),
        ];
    }
}
