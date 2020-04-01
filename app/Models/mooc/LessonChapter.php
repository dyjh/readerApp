<?php

namespace App\Models\mooc;

use App\Models\BaseModel;
use App\Models\mooc\traits\LessonedTrait;
use App\Models\platform\WhiteBoard;
use App\Models\ScopeSequence;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonChapter extends BaseModel
{
    use SoftDeletes;
    use LessonedTrait;
    use ScopeSequence;

    protected $table = 'lesson_chapters';

    protected $casts = [
        'stream_play_urls' => 'array'
    ];

    public const LIVE = 1;
    public const PLAYBACK = 0;
    public const IS_LIVING = 1;
    public const CLOSE_LIVE = 0;

    public function catalog()
    {
        return $this->belongsTo(LessonCatalog::class, 'catalog_id', 'id');
    }

    // 电子白板
    public function board()
    {
        return $this->hasOne(WhiteBoard::class, 'lesson_chapter_id', 'id');
    }
}
