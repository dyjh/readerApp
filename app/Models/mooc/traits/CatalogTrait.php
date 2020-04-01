<?php

namespace App\Models\mooc\traits;

use App\Models\mooc\Lesson;
use App\Models\mooc\LessonCatalog;
use App\Models\mooc\LessonChapter;
use Illuminate\Support\Facades\DB;

trait CatalogTrait
{
    /**
     * @var array
     */
    protected static $catalogOrder = [];

    protected static $chapterOrder = [];

    /**
     * @var string
     */
    protected $parentColumn = 'lesson_id';

    /**
     * @var string
     */
    protected $titleColumn = 'name';

    /**
     * @var string
     */
    protected $orderColumn = 'sequence';

    /**
     * @var \Closure
     */
    protected $queryCallback;

    /**
     * Get children of current node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(LessonChapter::class, 'catalog_id', 'id');
    }

    /**
     * Get parent of current node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }

    /**
     * @return string
     */
    public function getParentColumn()
    {
        return $this->parentColumn;
    }

    /**
     * Set parent column.
     *
     * @param string $column
     */
    public function setParentColumn($column)
    {
        $this->parentColumn = $column;
    }

    /**
     * Get title column.
     *
     * @return string
     */
    public function getTitleColumn()
    {
        return $this->titleColumn;
    }

    /**
     * Set title column.
     *
     * @param string $column
     */
    public function setTitleColumn($column)
    {
        $this->titleColumn = $column;
    }

    /**
     * Get order column name.
     *
     * @return string
     */
    public function getOrderColumn()
    {
        return $this->orderColumn;
    }

    /**
     * Set order column.
     *
     * @param string $column
     */
    public function setOrderColumn($column)
    {
        $this->orderColumn = $column;
    }

    /**
     * Set query callback to model.
     *
     * @param \Closure|null $query
     *
     * @return $this
     */
    public function withQuery(\Closure $query = null)
    {
        $this->queryCallback = $query;

        return $this;
    }

    /**
     * Format data to tree like array.
     *
     * @return array
     */
    public function toTree()
    {
        return $this->buildNestedArray();
    }

    /**
     * Build Nested array.
     *
     * @param array $nodes
     * @param int $parentId
     *
     * @return array
     */
    protected function buildNestedArray(array $nodes = [], $parentId = 0)
    {
        $branch = [];

        if (empty($nodes)) {
            $nodes = $this->allNodes();
        }

        foreach ($nodes as $node) {
            $chapters = $node['chapters'];
            $node['children'] = $chapters;
            $branch[] = $node;

        }

        return $branch;
    }

    /**
     * Get all elements.
     *
     * @return mixed
     */
    public function allNodes()
    {
        $self = new static();
        $lessonId = request()->get('lesson_id');
        return $self
            ->with(['chapters' => function ($query) {
                $query->orderBy('sequence');
            }])
            ->where('lesson_id', $lessonId)
            ->orderBy('sequence')
            ->get()
            ->toArray();
    }

    /**
     * Set the order of branches in the tree.
     *
     * @param array $orders
     * @return void
     */
    protected static function setOrder(array $orders)
    {
        foreach ($orders as $index => $order) {
            static::$catalogOrder[$order['id']] = $index;
            $childrenOrders = $order['children'];
            foreach ($childrenOrders as $seq => $childrenOrder) {
                static::$chapterOrder[$order['id']][$childrenOrder['id']] = $seq;
            }
        }
    }

    /**
     * Save tree order from a tree like array.
     *
     * @param array $tree
     * @param int $parentId
     */
    public static function saveCatalogOrder($tree = [], $parentId = 0)
    {
        if (empty(static::$catalogOrder)) {
            static::setOrder($tree);
        }
//        dd(static::$catalogOrder, static::$chapterOrder);

        foreach ($tree as $branch) {
            $catalog = LessonCatalog::find($branch['id']);

            $catalog->sequence = static::$catalogOrder[$branch['id']];
            $catalog->save();

            if (isset($branch['children'])) {
                static::saveChapterOrder($branch['children'], $branch['id']);
            }
        }
    }

    public static function saveChapterOrder($tree = [], $parentId = 0)
    {
        foreach ($tree as $branch) {
            $chapter = LessonChapter::find($branch['id']);

            // $chapter->catalog_id = $parentId;
            $chapter->sequence = static::$chapterOrder[$parentId][$branch['id']];
            $chapter->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete()
    {
        $this->where($this->parentColumn, $this->getKey())->delete();

        return parent::delete();
    }

//    /**
//     * {@inheritdoc}
//     */
//    protected static function boot()
//    {
//        parent::boot();
//
//        static::saving(function (Model $branch) {
//            $parentColumn = $branch->getParentColumn();
//
//            if (Request::has($parentColumn) && Request::input($parentColumn) == $branch->getKey()) {
//                throw new \Exception(trans('admin.parent_select_error'));
//            }
//
//            if (Request::has('_order')) {
//                $order = Request::input('_order');
//
//                Request::offsetUnset('_order');
//
//                static::tree()->saveOrder($order);
//
//                return false;
//            }
//
//            return $branch;
//        });
//    }
}
