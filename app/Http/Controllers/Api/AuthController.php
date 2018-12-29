<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UsersPost;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{

    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废  auth:api
        $this->middleware('jwt.auth', ['except' => ['login','signUp','passwordReset']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }
    /**

     * @param UsersPost $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(UsersPost $request)
    {

        $params = $request->validated();

        $user = User::where('mobile','=',$params['mobile'])->first();

        if(Hash::check($request->input('login_pass'), $user->login_pass)){
            $token = JWTAuth::fromUser($user);
        }

        // 使用 Auth 登录用户，如果登录成功，则返回 201 的 code 和 token，如果登录失败则返回
        /*if (! $token = auth('api')->attempt($attemptData)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }*/

        return $this->respondWithToken($token);
    }

    /**
     * 处理用户登出逻辑
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response(['message' => '退出成功']);
    }

    /**
     * 注册
     *
     * * @SWG\Post(path="/signUp",
     *   tags={"User"},
     *   summary="注册",
     *   produces={"application/json"},
     *     @SWG\Parameter(
     *     in="query",
     *     name="area_code",
     *     type="string",
     *     default="86",
     *     description="区号",
     *     required=true
     *   ),
     *     @SWG\Parameter(
     *     in="query",
     *     name="mobile",
     *     type="string",
     *     description="手机号",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     name="login_pass",
     *     type="string",
     *     format="string",
     *     description="密码",
     *     required=true,
     *   ),
     *    @SWG\Parameter(
     *     in="query",
     *     name="code",
     *     type="string",
     *     format="string",
     *     description="验证码",
     *     required=true
     *   ),
     *    @SWG\Parameter(
     *     in="query",
     *     name="invite_code",
     *     type="string",
     *     format="string",
     *     description="邀请码",
     *     required=false
     *   ),
     *   @SWG\Response(
     *     response="200",
     *     description="成功"
     *   ),
     *   @SWG\Response(
     *     response="422",
     *     description="验证失败"
     *   ),
     *
     * )
     *
     * @param UsersPost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(UsersPost $request)
    {
        $postUser = [
            'area_code' => $request->get('area_code', 86),
            'mobile' => $request->get('mobile'),
            'login_pass' => Hash::make($request->get('login_pass')),
        ];

        //判断用户是否注册过
        $user_id = User::where('mobile',$postUser['mobile'])->value('user_id');

        if ($user_id) {
            return $this->responseSuccess([],422,'此手机号已经注册过了');
        }

        $user = User::create($postUser);
        $token = JWTAuth::fromUser($user);

        return $this->responseSuccess([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);

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
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->responseSuccess([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }

    /**
     * @param UsersPost $request
     */
    public function passwordReset(UsersPost $request)
    {
        $message = $request->messages();
        dd($message);
    }
}
