<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class Speaker extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'humanitarian' => 'Works with Humanitarian Actions',
        'laracast-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Twitter Influencer',
        'youtube-influencer' => 'Youtube Influencer',
        'open-source-contributor' => 'Open Source Contributor / Maintainer',
        'unique-perspective' => 'Unique Perspective',
    ];

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            SpatieMediaLibraryFileUpload::make('avatar')
                ->collection('avatars')
                ->avatar()
                ->imageEditor()
                ->circleCropper()
                ->directory(directory: 'avatars')
                // ->preserveFilenames(true)
                // ->storeFileNamesIn('avatar')
                ->maxSize(size: 1024 * 1024 * 10),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\MarkdownEditor::make('bio')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('twitter_handle')
                ->maxLength(255),
            Forms\Components\Section::make('Qualifications')->schema([
                Forms\Components\CheckboxList::make('qualifications')
                    ->columnSpanFull()
                    ->searchable()
                    ->bulkToggleable(true)
                    ->columns(3)
                    ->options(self::QUALIFICATIONS),
            ])
        ];
    }
}
