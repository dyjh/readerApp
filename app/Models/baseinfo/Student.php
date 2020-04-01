<?php

namespace App\Models\baseinfo;

use App\Common\Definition;
use App\Models\mooc\StudentLessonHistory;
use App\Models\mooc\StudentLessons;
use App\Models\shares\PrivateBook;
use App\Models\BaseModel;
use App\Models\stores\Traits\PayTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use SoftDeletes, PayTrait;

    protected $table = 'students';

    protected $hidden = ['password'];

    protected $fillable =  [
        'name',
        'realname',
        'password',
        'avatar',
        'school_id',
        'grade_id',
        'ban_id',
        'school_name',
        'grade_name',
        'ban_name',
        'phone',
        'province',
        'city',
        'district',
        'total_beans',
        'read_count',
        'share_count',
        'in_blacklist'
    ];

    protected $casts = [
        'status' => 'boolean',
        'in_blacklist' => 'boolean',
    ];

    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value): string
    {
        return $value ? starts_with($value, 'http') ? $value : \Storage::disk('qiniu')->url($value) : '';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function scopeStatus($query, $value)
    {
        return $query->where('status', $value);
    }

    /**
     * 属于用户上传的图书
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author marhone
     */
    public function privateBooks()
    {
        return $this->hasMany(PrivateBook::class, 'student_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(StudentLessons::class, 'student_id', 'id');
    }

    public function lessons_histories()
    {
        return $this->hasMany(StudentLessonHistory::class, 'student_id', 'id');
    }

    public static function getUserLessons(int $type)
    {
        return \Auth::user()->lessons()->where('payment_statement', 1)->with(['lesson' => function($query){
            $query->select(['id','tag','name','cover','broadcast_day_begin','broadcast_start_at','lesson_hour_count']);
        }])->whereHas('lesson', function ($query) use ($type) {
            $query->where('is_streamed', $type);
        })->select(['id','trade_no','paid_price','created_at','lesson_id'])->latest()->get();
    }

    // 书豆记录
    public function beanRecords()
    {
        return $this->hasMany(BeanRecord::class, 'student_id', 'id');
    }

    public function acquireBeanBy($delta, $by)
    {
        if ($by == Definition::BOOK_BEAN_CHANGE_BY_RENT) {
            $delta *= -1;
        }
        $before = $this->getAttribute('total_beans');
        $after = $before + $delta;
        $this->setAttribute('total_beans', $after);
        $this->beanRecords()->create([
            'changed_by' => $by,
            'amount' => $delta,
            'changed_at' => Carbon::now()->toDateTimeString(),
            'before_beans_total' => $before,
            'after_beans_total' => $after,
        ]);
        $this->save();
    }

    public function blacklist(bool $state)
    {
        $old = $this->getAttribute('in_blacklist');
        if ($old != $state) {
            $this->setAttribute('in_blacklist', $state);
            $this->save();
        }
    }
}
