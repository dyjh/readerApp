<?php


namespace App\Services\baseinfo;


use App\Exceptions\api\CommonServiceException;
use App\Exceptions\api\SMSCodeExpiredException;
use App\Models\baseinfo\Ban;
use App\Models\baseinfo\Grade;
use App\Models\baseinfo\School;
use App\Models\baseinfo\Student;
use App\Repositories\baseinfo\StudentRepository;
use App\Services\common\SMSNotificationServices;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentsService
{
    /**
     * @var StudentRepository
     */
    private $repository;
    /**
     * @var SMSNotificationServices
     */
    private $notificationServices;

    /**
     * StudentsService constructor.
     * @param StudentRepository $repository
     * @param SMSNotificationServices $notificationServices
     */
    public function __construct(StudentRepository $repository, SMSNotificationServices $notificationServices)
    {
        $this->repository = $repository;
        $this->notificationServices = $notificationServices;
    }

    /**
     * @param array $attributes
     * @return mixed|null
     * @throws CommonServiceException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @author marhone
     */
    public function register(array $attributes)
    {
        $phone = $attributes['phone'];
        $sms_code = $attributes['sms_code'];

        /*try {
            $verification = $this->notificationServices->smsVerifier($phone, $sms_code);
        } catch (SMSCodeExpiredException $exception) {
            throw new CommonServiceException($exception->getMessage());
        }

        if (!$verification) {
            throw new CommonServiceException('短信验证码不正确');
        }*/

        try {
            $school = School::findOrFail($attributes['school_id'] ?? null);
            $grade = Grade::findOrFail($attributes['grade_id'] ?? null);
            $ban = Ban::findOrFail($attributes['ban_id'] ?? null);
        } catch (ModelNotFoundException $exception) {
            return null;
        }

        $attributes['school_name'] = $school->getAttribute('name');
        $attributes['city'] = $school->getAttribute('city');
        $attributes['province'] = $school->getAttribute('province');
        $attributes['district'] = $school->getAttribute('district');
        $attributes['ban_name'] = $ban->getAttribute('name');
        $attributes['grade_name'] = $grade->getAttribute('name');
        // 用户密码
        $attributes['password'] = bcrypt($attributes['password']);
        if (empty($attributes['avatar'])) {
            // default avatar
            $attributes['avatar'] = url('vendor/presets/avatar.png');
        }

        $student = $this->repository->create($attributes);
        return $student;
    }

    /**
     * 修改用户基本信息
     * @param array $attributes
     * @return mixed|null
     * @throws CommonServiceException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @author marhone
     */
    public function modify(array $attributes)
    {
        try {
            $school = School::findOrFail($attributes['school_id'] ?? null);
            $grade = Grade::findOrFail($attributes['grade_id'] ?? null);
            $ban = Ban::findOrFail($attributes['ban_id'] ?? null);
        } catch (ModelNotFoundException $exception) {
            return null;
        }

        $attributes['school_name'] = $school->getAttribute('name');
        $attributes['city'] = $school->getAttribute('city');
        $attributes['province'] = $school->getAttribute('province');
        $attributes['district'] = $school->getAttribute('district');
        $attributes['ban_name'] = $ban->getAttribute('name');
        $attributes['grade_name'] = $grade->getAttribute('name');
        if (empty($attributes['avatar'])) {
            // default avatar
            $attributes['avatar'] = url('vendor/presets/avatar.png');
        }

        $student = $this->repository->update($attributes, auth('api')->id());
        return $student;
    }

    // todo::接入短信API
    public function sendBindCode(string $phone)
    {
        $code = $this->notificationServices->smsSendBindingCode($phone);

        return $code;
    }

    /**
     * @param array $attributes
     * @return mixed
     * @throws CommonServiceException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @author marhone
     */
    public function modifyPhone(array $attributes)
    {
        $phone = $attributes['phone'];
        $sms_code = $attributes['sms_code'];

        try {
            $verification = $this->notificationServices->smsVerifier(auth('api')->user()->phone, $sms_code);
        } catch (SMSCodeExpiredException $exception) {
            throw new CommonServiceException($exception->getMessage());
        }

        if (!$verification) {
            throw new CommonServiceException('短信验证码不正确');
        }

        $student = $this->repository->update($attributes, auth('api')->id());
        return $student;
    }

    /**
     * @param array $attributes
     * @return mixed
     * @throws CommonServiceException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @author marhone
     */
    public function resetPassword(array $attributes)
    {
        $phone = $attributes['phone'];
        $password = bcrypt($attributes['password']);
        $sms_code = $attributes['sms_code'];

        try {
            $verification = $this->notificationServices->smsVerifier($phone, $sms_code);
        } catch (SMSCodeExpiredException $exception) {
            throw new CommonServiceException($exception->getMessage());
        }


        if (!$verification) {
            throw new CommonServiceException('短信验证码不正确');
        }

        /** @var Student $student */
        $student = $this->repository->findByField('phone', $phone)->first();
        if ($student) {
            $student->setAttribute('password', $password);
            $student->save();
        }

        return $student;
    }
}