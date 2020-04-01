<?php

namespace App\Http\Controllers\api\shares;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Exceptions\api\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\shares\UploadPrivateBookRequest;
use App\Http\Resources\shares\PrivateBookCollection;
use App\Http\Resources\shares\PrivateBookResource;
use App\Models\shares\PrivateBook;
use App\Services\shares\PrivateBookService;
use Illuminate\Http\Request;

class PrivateBooksController extends Controller
{
    use APIResponseTrait;

    /**
     * @var PrivateBookService
     */
    private $privateBookService;

    /**
     * PrivateBooksController constructor.
     * @param PrivateBookService $privateBookService
     */
    public function __construct(PrivateBookService $privateBookService)
    {
        $this->privateBookService = $privateBookService;
    }

    // 图书上传到共享书籍
    public function upload(UploadPrivateBookRequest $request)
    {
        $student = auth('api')->user();
        $attributes = $request->only(['isbn']);

        $res = $this->privateBookService->uploadBookToShare($student, $attributes['isbn']);
        if (!$res) {
            $this->json('上传图书失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json('图书上传成功');
    }

    // 用户上传书籍列表
    public function list(Request $request)
    {
        $inShelf = $request->get('is_in_shelf');
        $list = $this->privateBookService->listBookByStudent(auth('api')->id(), $inShelf);
        return new PrivateBookCollection($list);
    }

    // 获取用户上传书籍详情
    public function show(PrivateBook $privateBook)
    {
        throw_if(
            !auth('api')->user()->can('access', $privateBook),
            AccessDeniedException::class,
            '无权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        // 获取用户对应的共享书籍
        $sharedBook = $this->privateBookService->showSharedBook($privateBook);

        return $this->json(new PrivateBookResource($sharedBook));
    }

    // 上架书籍
    public function putaway(PrivateBook $privateBook)
    {
        throw_if(
            !auth('api')->user()->can('access', $privateBook),
            AccessDeniedException::class,
            '无权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->privateBookService->bookPutaway($privateBook);
        if (!$result) {
            return $this->json('操作失败', ResponseStatement::STATUS_ERROR);
        }
        return $this->json('书籍已上架');
    }

    // 下架书籍
    public function recycle(PrivateBook $privateBook)
    {
        throw_if(
            !auth('api')->user()->can('access', $privateBook),
            AccessDeniedException::class,
            '无权限访问',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->privateBookService->bookRecycle($privateBook);
        if (!$result) {
            return $this->json('操作失败', ResponseStatement::STATUS_ERROR);
        }
        return $this->json('书籍已下架');
    }
}
