<?php

namespace App\Models\stores;

use App\Models\BaseModel;
use App\Models\HasOwner;
use App\Http\Filters\Filterable;
use App\Models\stores\Contracts\OrderInterface;
use App\Models\stores\Observers\OrderObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\abstracts\AbstractPayable;

class Order extends AbstractPayable implements OrderInterface
{
    use SoftDeletes, HasOwner, Filterable;

    protected $table = 'orders';

    protected $casts = [
        'student_id'   => 'integer',
        'title'        => 'string',
        'trade_no'     => 'string',
        'trans_no'     => 'string',
        'tag_price'    => 'double',
        'total'        => 'double',
        'refund_total' => 'double',
        'total_amount' => 'integer',
        'paid_at'      => 'string',
        'statement'    => 'integer',
        'payment_method' => 'integer',
    ];

    protected $fillable = [
        'student_id',
        'title',
        'trade_no',
        'trans_no',
        'tag_price',
        'total',
        'refund_total',
        'total_amount',
        'paid_at',
        'statement',
        'payment_method',
    ];

    protected $dates = [
        'paid_at'
    ];

    public static function boot()
    {
        parent::boot();
        static::observe(OrderObserver::class);
    }

    public function getStatusTextAttribute(): string
    {
        return trans('order.statuses.' . $this->getAttribute('statuses'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function postage()
    {
        return $this->hasOne(OrderPostage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(ProductBookComment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotal(): float
    {
        return $this->getAttribute('total');
    }

    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    public function getTradeNo(): string
    {
        return $this->getAttribute('trade_no');
    }

    public function getStudentId(): int
    {
        return $this->getAttribute('student_id');
    }
    
    /**
     * @return Carbon
     */
    public function getExpiredAt(): Carbon
    {
        return Carbon::now()->addMinutes($this->getExpiredMinutes());
    }

    /**
     * @return integer
     */
    public function getExpiredMinutes(): int
    {
        return 30;
    }
}
