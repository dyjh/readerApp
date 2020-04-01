<?php

namespace App\Http\Controllers\api\baseinfo;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Exceptions\api\CommonServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\baseinfo\BindPhoneRequest;
use App\Http\Requests\baseinfo\StudentChangePhoneRequest;
use App\Http\Requests\baseinfo\StudentCreationRequest;
use App\Http\Requests\baseinfo\StudentPasswordForgetRequest;
use App\Http\Requests\baseinfo\StudentUpdatingRequest;
use App\Http\Resources\baseinfo\StudentResource;
use App\Services\baseinfo\StudentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Prettus\Validator\Exceptions\ValidatorException;
use RongCloud\RongCloud;

class StudentsController extends Controller
{
    use APIResponseTrait;

    /**
     * @var StudentsService
     */
    private $service;

    /**
     * StudentsController constructor.
     * @param StudentsService $service
     */
    public function __construct(StudentsService $service)
    {
        $this->service = $service;

        // $this->middleware('throttle:3:5', ['only' => ['bindPhoneCode']]);
    }

    /**
     * 用户注册
     * @param StudentCreationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     * @author marhone
     */
    public function signUp(StudentCreationRequest $request)
    {
        $attributes = $request->only([
            'name',
            'password',
            'school_id',
            'grade_id',
            'ban_id',
            'phone',
            'sms_code'
        ]);

        try {
            $student = $this->service->register($attributes);
        } catch (CommonServiceException $e) {
            return $this->json($e->getMessage(), ResponseStatement::STATUS_ERROR);
        }

        if (is_null($student)) {
            return $this->json('创建用户失败', ResponseStatement::STATUS_ERROR);
        }

        $RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
        $user = [
            'id'       => "$student->id",
            'name'     => "$student->name",//用户名称
            'portrait' => 'http://www.mrjune.top/vendor/presets/avatar.png' //用户头像
        ];
        $register = $RongSDK->getUser()->register($user);
        if ($register['code'] == 200) {
            $student->rong_cloud_token = $register['token'];
            $student->save();
        }

        return $this->json('用户注册成功');
    }

    /**
     * 获取用户信息
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function getProfile()
    {
        $student = auth('api')->user();
        return $this->json(new StudentResource($student));
    }

    /**
     * 更新用户基本信息
     * @param StudentUpdatingRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     * @throws CommonServiceException
     * @author marhone
     */
    public function updateProfile(StudentUpdatingRequest $request)
    {
        $attributes = $request->only([
            'id',
            'name',
            'school_id',
            'grade_id',
            'ban_id',
            'phone',
            'sms_code'
        ]);

        $student = $this->service->modify($attributes);
        if (is_null($student)) {
            return $this->json('修改用户信息失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json('修改用户信息成功');
    }

    public function updateAvatar(Request $request)
    {
        $user = auth('api')->user();
        if ($request->has('image')) {
            $image = $request->post('image');

            $user->update([
                'avatar' => $image
            ]);
            $RongSDK = new RongCloud(config('latrell-rcloud.app_key'), config('latrell-rcloud.app_secret'));
            $user = [
                'id'       => $user->id,
                'name'     => $user->name,
                'portrait' => $image
            ];
            $RongSDK->getUser()->update($user);
            return $this->json();
        }

        return $this->json('图片地址必传', ResponseStatement::STATUS_ERROR);
    }

    /**
     * 更改手机号
     * @param StudentChangePhoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidatorException
     * @author marhone
     */
    public function changePhone(StudentChangePhoneRequest $request)
    {
        $attributes = $request->only([
            'phone',
            'sms_code'
        ]);

        try {
            $student = $this->service->modifyPhone($attributes);
        } catch (CommonServiceException $e) {
            return $this->json($e->getMessage(), ResponseStatement::STATUS_ERROR);
        }
        if (is_null($student)) {
            return $this->json('修改用户手机号失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json('修改用户手机号成功');
    }

    public function resetPassword(StudentPasswordForgetRequest $request)
    {
        $attributes = $request->only([
            'phone',
            'password',
            'sms_code'
        ]);

        try {
            $student = $this->service->resetPassword($attributes);
        } catch (CommonServiceException $e) {
            return $this->json($e->getMessage(), ResponseStatement::STATUS_ERROR);
        }
        if (is_null($student)) {
            return $this->json('重置用户密码失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json('重置用户密码成功');
    }

    /**
     * 获取绑定手机的短信验证码
     * @param BindPhoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function bindPhoneCode(BindPhoneRequest $request)
    {
        $phone = $request->post('phone');
        $res = $this->service->sendBindCode($phone);

        if (!$res) {
            return $this->json('短信验证码发送失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json("短信验证码发送成功: $res");
    }

    public function qiniuUpload(Request $request)
    {
//        $filename = $request->post('filename', '');
//        $ext = explode('.', $filename)[1];
//        if (!in_array($ext, [
//            'jpeg',
//            'jpg',
//            'png',
//            'gif'
//        ])) {
//            return $this->json('不支持的文件类型');
//        }

        $params = [
            'path' => Storage::disk('qiniu')->url(''),
            'uptoken' => Storage::disk('qiniu')->uploadToken(),
        ];

        return $this->json($params);
    }
}
