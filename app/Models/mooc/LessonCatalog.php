<?php

namespace App\Models\mooc;

use App\Admin\Extensions\widgets\TreeViewer;
use App\Models\mooc\traits\CatalogTrait;
use App\Models\mooc\traits\LessonedTrait;
use App\Models\ScopeSequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonCatalog extends Model
{
    use SoftDeletes;
    use LessonedTrait;
    use ScopeSequence;
    use CatalogTrait;

    protected $table = 'lesson_catalogs';

    protected $fillable = [
        'lesson_id', 'name', 'desc', 'sequence'
    ];

    public static function tree(\Closure $callback = null)
    {
        return new TreeViewer(new static(), $callback);
    }

    public function chapters()
    {
        return $this->hasMany(LessonChapter::class, 'catalog_id', 'id');
    }
}
