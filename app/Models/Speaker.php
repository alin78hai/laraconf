<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Forms;

class Speaker extends Model
{
    use HasFactory;

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
            Forms\Components\FileUpload::make('avatar')
                ->avatar()
                ->imageEditor()
                ->circleCropper()
                ->maxSize(1024 * 1024 * 3),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\MarkdownEditor::make('bio')
                ->required()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('twitter_handle')
                ->required()
                ->maxLength(255),
            Forms\Components\Section::make('Qualifications')->schema([
                Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable(true)
                ->columns(3)
                ->options([
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
                ]),
            ])

        ];
    }
}
