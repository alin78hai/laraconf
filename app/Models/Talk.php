<?php

namespace App\Models;

use App\Enums\{ TalkStatus, TalkLength };
use Filament\Forms;
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

    public static function getFormSchema()
    {
        return [
            Forms\Components\Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->createOptionForm(Speaker::getFormSchema())
                ->editOptionForm(Speaker::getFormSchema())
                ->required(),
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
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
