<?php

namespace App\Http\Controllers\api\shares;

use App\Common\ResponseStatement;
use App\Common\traits\APIResponseTrait;
use App\Exceptions\api\AccessDeniedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\shares\BorrowCommentRequest;
use App\Http\Resources\shares\BorrowCommentCollection;
use App\Http\Resources\shares\SharedBookCollection;
use App\Http\Resources\shares\SharedBookOwnerCollection;
use App\Http\Resources\shares\SharedBookResource;
use App\Models\shares\SharedBook;
use App\Services\shares\BorrowCommentService;
use App\Services\shares\SharedBookService;

class SharedBooksController extends Controller
{
    use APIResponseTrait;

    /**
     * @var SharedBookService
     */
    private $bookService;

    /**
     * @var BorrowCommentService
     */
    private $commentService;

    /**
     * SharedBooksController constructor.
     * @param SharedBookService $bookService
     * @param BorrowCommentService $commentService
     */
    public function __construct(SharedBookService $bookService, BorrowCommentService $commentService)
    {
        $this->bookService = $bookService;
        $this->commentService = $commentService;
    }

    // 获取校内图书
    public function listBySchool()
    {
        $student = auth('api')->user();
        $list = $this->bookService->findBookByArea($student, 'school');

        return new SharedBookCollection($list);
    }

    // 获取班级内图书
    public function listByBan()
    {
        $student = auth('api')->user();
        $list = $this->bookService->findBookByArea($student, 'ban');

        return new SharedBookCollection($list);
    }

    // 学校内书籍拥有者
    public function listOwnersBySchool($id)
    {
        $owners = $this->bookService->findBookOwnersByArea($id, 'school');
        return new SharedBookOwnerCollection($owners);
    }

    // 班级内书籍拥有者
    public function listOwnersInBan($id)
    {
        $owners = $this->bookService->findBookOwnersByArea($id, 'ban');
        return new SharedBookOwnerCollection($owners);
    }

    // 共享图书详情
    public function show($id)
    {
        $book = $this->bookService->findBookByID($id);
        return $this->json(new SharedBookResource($book));
    }

    public function comments($id)
    {
        $comments = $this->commentService->commentByBook($id);
        return new BorrowCommentCollection($comments);
    }

    public function review(BorrowCommentRequest $request, SharedBook $sharedBook)
    {
        $student = auth('api')->user();
        $content = $request->get('content');
        throw_if(
            !auth('api')->user()->can('commentAsRenter', $sharedBook),
            AccessDeniedException::class,
            '无法评论该图书',
            ResponseStatement::ACCESS_DENIED
        );

        $result = $this->commentService->publishBookReview($student, $sharedBook, $content);
        if (!$result) {
            return $this->json('操作失败', ResponseStatement::STATUS_ERROR);
        }

        return $this->json('发表评论成功');
    }
}