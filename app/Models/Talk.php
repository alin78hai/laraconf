<?php

namespace App\Models;

use App\Enums\{ TalkStatus, TalkLength };
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
        'is_newtalk' => 'boolean',
        'status' => TalkStatus::class,
        'length' => TalkLength::class,
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function scopeApproved($query): Builder
    {
        return $query->where('status', TalkStatus::APPROVED);
    }

    public static function getFormSchema()
    {
        return [
            Forms\Components\TextInput::make('title')
                ->columnSpanFull()
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->createOptionForm(Speaker::getFormSchema())
                ->editOptionForm(Speaker::getFormSchema())
                ->required(),
            Forms\Components\MarkdownEditor::make('abstract')
                ->required()
                ->columnSpanFull(),
            Forms\Components\Select::make('length')
                ->options(TalkLength::asSelectArray())
                ->required(),
            Forms\Components\Select::make('status')
                ->options(TalkStatus::asSelectArray())
                ->required(),
            Forms\Components\Toggle::make('is_newtalk')
                ->required(),
        ];
    }
}
