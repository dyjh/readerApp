<?php

namespace App\Repositories\mooc;

use App\Models\mooc\Lesson;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LessonRepository.
 *
 * @package namespace App\Repositories\Mooc;
 */
interface LessonRepository extends RepositoryInterface
{
    public function user_lessons() :array;

    public function pay(Lesson $lesson, string $platform, string $type);

    public function alipayNotify($data);

    public function wxpayNotify($data);
}
