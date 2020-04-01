<?php

namespace App\Http\Controllers\api\auth;

use App\Common\traits\APIResponseTrait;
use App\Http\Requests\auth\AuthRequest;
use App\Models\baseinfo\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use APIResponseTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->only(['phone', 'password']);

        if (!Student::where('phone', $request->post('phone'))->exists()) {
            return response()->json([
                'status' => -1,
                'message' => '用户账户不存在'
            ], 200);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => -1,
                'message' => '用户账户密码不匹配'
            ], 200);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 1,
            'data' => '用户登出成功'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return $this->json([
            'token' => 'bearer ' . $token,
            'rong_cloud_token' => auth('api')->user()->rong_cloud_token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
