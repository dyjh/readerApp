<?php

namespace App\Http\Controllers\api\shares;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Exceptions\api\AccessDeniedException;
use App\Exceptions\api\shares\RentBookException;
use App\Exceptions\api\shares\RentBookStateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\shares\RentBookRequest;
use App\Http\Resources\shares\BookRentCollection;
use App\Http\Resources\shares\BookRentResource;
use App\Models\shares\StudentsBooksRent;
use App\Services\shares\StudentsBooksRentService;

class StudentsBooksRentsController extends Controller
{
    use APIResponseTrait;

    /**
     * @var StudentsBooksRentService
     */
    private $booksRentService;

    /**
     * StudentsBooksRentsController constructor.
     * @param StudentsBooksRentService $booksRentService
     */
    public function __construct(StudentsBooksRentService $booksRentService)
    {
        $this->booksRentService = $booksRentService;
    }

    // 借书申请
    public function rentApply(RentBookRequest $request)
    {
        $attributes = $request->only([
            'shared_book_id',
            'owner_id'
        ]);

        throw_if(
            auth('api')->user()->getAttribute('in_blacklist'),
            AccessDeniedException::class,
            '黑名单用户不允许借书',
            ResponseStatement::ACCESS_DENIED
        );

        throw_if(
            auth('api')->id() == $attributes['owner_id'],
            AccessDeniedException::class,
            '操作不被允许',
            ResponseStatement::ACCESS_DENIED
        );

        try {
            $result = $this->booksRentService->applyForBookRent(auth('api')->user(), $attributes['shared_book_id'], $attributes['owner_id']);
        } catch (RentBookException $exception) {
            return $this->json($exception->getMessage(), ResponseStatement::STATUS_ERROR);
        }

        return $this->json('用户借书申请成功');
    }

    // 同意申请
    public function rentAgree(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('updateAsLender', $booksRent),
            RentBookStateException::class, '无权权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->booksRentService->grantForBookRent($booksRent);
        throw_if(
            !$result,
            RentBookStateException::class,
            '重复操作',
            ResponseStatement::OPERATION_REPEAT
        );

        return $this->json('已同意借书申请');
    }

    // 取消借书申请
    public function rentCancel(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('updateAsRenter', $booksRent),
            RentBookStateException::class, '无权权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->booksRentService->cancelForBookRent($booksRent);
        throw_if(
            !$result,
            RentBookStateException::class,
            '重复操作',
            ResponseStatement::OPERATION_REPEAT
        );

        return $this->json('已取消图书借阅申请');
    }

    // 拒绝借书申请
    public function rentReject(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('updateAsLender', $booksRent),
            RentBookStateException::class, '无权权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->booksRentService->rejectForBookRent($booksRent);
        // dd(['@controller' => $result]);
        throw_if(
            !$result,
            RentBookStateException::class,
            '重复操作',
            ResponseStatement::OPERATION_REPEAT
        );

        return $this->json('已拒绝图书借阅申请');
    }

    // 借书归还申请
    public function returnApply(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('updateAsRenter', $booksRent),
            RentBookStateException::class, '无权权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $response = $this->booksRentService->applyForBookReturn($booksRent);
        if (!$response['status']) {
            return $this->json($response['message'], ResponseStatement::STATUS_ERROR);
        }

        return $this->json($response['message']);
    }

    // 借书归还确认
    public function returnAgree(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('updateAsLender', $booksRent),
            RentBookStateException::class, '无权权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->booksRentService->grantForBookReturn($booksRent);
        throw_if(
            !$result,
            RentBookStateException::class,
            '重复操作',
            ResponseStatement::OPERATION_REPEAT
        );

        return $this->json('已确认图书归还');
    }

    // 借入列表
    public function rents()
    {
        $list = $this->booksRentService->rentListByStudent(auth('api')->id());
        return new BookRentCollection($list);
    }

    // 借入记录详情
    public function rentDetail(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('accessAsRenter', $booksRent),
            RentBookStateException::class,
            '不属于该用户的借书记录',
            ResponseStatement::ACCESS_DENIED
        );
        return $this->json(new BookRentResource($booksRent));
    }

    // 借出详情
    public function lendDetail(StudentsBooksRent $booksRent)
    {
        throw_if(
            !auth('api')->user()->can('accessAsLender', $booksRent),
            RentBookStateException::class,
            '不属于该用户的借出记录',
            ResponseStatement::ACCESS_DENIED
        );
        return $this->json(new BookRentResource($booksRent));
    }
}
