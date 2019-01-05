<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UsersPost;
use App\Models\Captcha;
use App\Models\CoinStaticFreed;
use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        //$this->middleware('jwt.auth', ['except' => ['signIn','signUp','passwordReset']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
    }

    /**
     ** @SWG\Post(
     *     path="/signIn",
     *     tags={"User"},
     *     summary="登陆",
     *     produces={"application/json"},
     *   @SWG\Parameter(
     *      in="query",
     *      name="mobile",
     *      type="string",
     *      default="",
     *      description="手机号",
     *      required=true
     *   ),
     *   @SWG\Parameter(
     *      in="query",
     *      name="login_pass",
     *      type="string",
     *      default="",
     *      description="密码",
     *      required=true
     *   ),
     *   @SWG\Response(
     *      response="200",
     *      description="成功"
     *   ),
     *   @SWG\Response(
     *      response="422",
     *      description="验证失败"
     *   ),
     * )
     * @param UsersPost $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function signIn(UsersPost $request)
    {
        $params = $request->validated();
        $user = User::where('mobile', '=', $params['mobile'])->first();

        if (User::userPasswordIsCorrect($user->uid, $params['login_pass'])) {
            $token = JWTAuth::fromUser($user);
        } else {
            return $this->error([], 422, '账号或者密码不正确');
        }
        //静态释放
        CoinStaticFreed::staticFreed(1);
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
     * * @SWG\Post(
     *     path="/signUp",
     *     tags={"User"},
     *     summary="注册",
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         in="query",
     *         name="area_code",
     *         type="string",
     *         default="86",
     *         description="区号",
     *         required=true
     *   ),
     *     @SWG\Parameter(
     *       in="query",
     *       name="mobile",
     *       type="string",
     *       description="手机号",
     *       required=true
     *   ),
     *   @SWG\Parameter(
     *       in="query",
     *       name="login_pass",
     *       type="string",
     *       format="string",
     *       description="密码",
     *       required=true,
     *   ),
     *    @SWG\Parameter(
     *       in="query",
     *       name="code",
     *       type="string",
     *       format="string",
     *       description="验证码",
     *       required=true
     *   ),
     *    @SWG\Parameter(
     *       in="query",
     *       name="invite_code",
     *       type="string",
     *       format="string",
     *       description="邀请码",
     *       required=false
     *   ),
     *   @SWG\Response(
     *      response="200",
     *      description="成功"
     *   ),
     *   @SWG\Response(
     *      response="422",
     *      description="验证失败"
     *   ),
     * )
     *
     * @param UsersPost $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(UsersPost $request)
    {
        try {
            DB::beginTransaction();
            $postUser = [
                'area_code' => $request->get('area_code', 86),
                'mobile' => $request->get('mobile'),
                'login_pass' => Hash::make($request->get('login_pass')),
                'invite_code' => User::createUserInviteCode(),
            ];
            if ($request->filled('invite_code')) {
                $inviteUserId = User::getInviteUserIdByInviteCode($request->invite_code);
                if ($inviteUserId) {
                    $postUser['invite_uid'] = $inviteUserId;
                }
            }

            $user = User::create($postUser);
            UserInvite::updateUserInviteByUserId($user->uuid);
            $token = JWTAuth::fromUser($user);
            DB::commit();
            return $this->success([
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL()
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], 422, $e->getMessage());
        }

    }

    /** userInfo
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth('api')->user();
        return $this->success($user);
        //return response()->json(auth('api')->user());
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     *
     ** @SWG\Post(
     *      path="/refreshToken",
     *      tags={"User"},
     *      summary="刷新token",
     *      produces={"application/json"},
     *      security={{ "Bearer":{}}},
     *      @SWG\Response(
     *      response="200",
     *      description="添加成功"
     *   ),
     *     @SWG\Response(
     *      response="422",
     *      description="验证失败"
     *   ),
     * )
     *
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
        return $this->success([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }


    public function passwordReset(UsersPost $request)
    {
        $userId = JWTAuth::user()->uid;
        if (!User::userPasswordIsCorrect($userId, $request->post('login_pass_old'))) {
            return $this->success([], 422, '密码错误');
        }
        //更新密码
        if (!User::updatePassword($userId, $request->post('login_pass_new'))) {
            return $this->error();
               return $this->success();
        }
    }

    /**
     * 忘记密码
     * ** @SWG\Post(
     *      path="/forgetPassword",
     *      tags={"User"},
     *      summary="忘记密码",
     *      produces={"application/json"},
     *      security={{ "Bearer":{}}},
     *      @SWG\Parameter(
     *         in="query",
     *         name="mobile",
     *         type="string",
     *         default="86",
     *         description="手机号",
     *         required=true
     *      ),
     *      @SWG\Parameter(
     *         in="query",
     *         name="login_pass",
     *         type="string",
     *         default="86",
     *         description="修改的密码",
     *         required=true
     *      ),
     *     @SWG\Parameter(
     *         in="query",
     *         name="code",
     *         type="string",
     *         default="",
     *         description="验证码",
     *         required=true
     *      ),
     *      @SWG\Response(
     *          response="200",
     *          description="修改成功"
     *     ),
     *     @SWG\Response(
     *       response="422",
     *       description="修改失败"
     *    ),
     * )
     *
     *
     * @param UsersPost $request
     */
    public function forgetPassword(UsersPost $request)
    {
        $mobile = $request->mobile;
        $type = 'reset_pwd';
        $code = $request->code;

        if (!User::judgeUserIdExistByMobile($mobile)) {
            return $this->error([],422,'用户不存在');
        }

        if (!Captcha::VerifySms($mobile,$code,$type)) {
            return $this->error([],422,'验证码错误');
        }
        //更新用户密码
        if (!User::updatePasswordByMobile($mobile,$request->login_pass)) {
            return $this->error([],422,'密码更新失败!');
        }
        return $this->success();

    }
}
