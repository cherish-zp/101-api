<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Emadadly\LaravelUuid\Uuids;


/**
 * App\Models\User
 *
 * @property string $uuid 唯一约束
 * @property int $uid 用户id
 * @property string $mobile 手机号
 * @property int $level 用户等级
 * @property float $invest_num 投资金额
 * @property float $cash_num 累计提现
 * @property string $login_pass 密码
 * @property string $pay_pass 支付密码
 * @property int $is_out 1:未出局 2 : 出局
 * @property int $invite_uid 邀请用户id
 * @property string $invite_code 邀请码
 * @property string $private_key 私钥
 * @property string $public_key 公钥
 * @property string $mnemonic 助记词
 * @property int $is_queued 1=已排队|2=未排队
 * @property float $dynamic_freed 动态释放数量 [伞下人员释放数量]
 * @property float $static_freeed 静态释放数量 [个人释放数量]
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @property string|null $deleted_at 删除时间
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User uuid($uuid, $first = true)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCashNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDynamicFreed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereInvestNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereInviteCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereInviteUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsQueued($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLoginPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMnemonic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePayPass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePrivateKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStaticFreeed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUuid($value)
 * @mixin \Eloquent
 * @property int $status 1=正常|2=禁用
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereStatus($value)
 * @property string $area_code 区域码
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereAreaCode($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use Uuids, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
    /**
     * @var string
     */
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'mobile', 'login_pass', 'pay_pass', 'reg_time', 'amount', 'invite_code', 'invite_uid',
    ];
    protected $primaryKey = 'uid';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'private_key', 'public_key',
    ];

    /**
     * @var bool
     */
    public $timestamps = true;

    // Rest omitted for brevity
    /**
     * @var int 已出局
     */
    public static $isOutYes = 1;
    /**
     * @var int 未出局
     */
    public static $isOutNo = 2;
    /**
     * @var int 已进场
     */
    public static $isQueuedYes = 1;
    /**
     * @var int 未进场
     */
    public static $isQueuedNo = 2;

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

    /**
     * 验证用户密码
     * @param int $uid
     * @param $password
     * @return bool
     */
    public static function userPasswordIsCorrect(int $uid, $password): bool
    {
        $login_pass = self::getUserPassword($uid);

        return Hash::check($password, $login_pass) ? true : false;
    }

    /**
     * 获取用户密码
     * @param int $uid
     * @return string
     */
    public static function getUserPassword(int $uid): string
    {
        return self::whereUserId($uid)->value('login_pass');
    }

    /**
     * 更新密码
     * @param $uid
     * @param $password
     * @return bool|int
     */
    public static function updatePassword($uid, $password)
    {
        return self::whereUserId($uid)->update(['login_pass' => Hash::make($password)]);
    }

    /**
     * 通过邀请码获取邀请人uid
     * @param $inviteCode
     * @return int
     * @throws \Exception
     */
    public static function getInviteUserIdByInviteCode($inviteCode)
    {
        $inviteUser = self::getUserInfo(['invite_code' => $inviteCode], ['uid']);
        if (!$inviteUser)
            throw new \Exception('邀请码无效');
        return $inviteUser->uid;
    }


    public static function createUserInviteCode()
    {
        $inviteCode = createInviteCode(8);
        $inviteUser = self::getUserInfo(['invite_code' => $inviteCode], ['uid']);
        if ($inviteUser) {
            self::createUserInviteCode();
        }
        return $inviteCode;
    }

    /**
     * 获取用户信息
     * @param array $where
     * @param array $fileds
     * @return User|\Illuminate\Database\Eloquent\Model|object|null
     */
    public static function getUserInfo(array $where, array $fileds = [])
    {
        return self::where($where)->first($fileds);

    }

    /**
     * @param $mobile
     * @return bool
     */
    public  static function judgeUserIdExistByMobile($mobile) : bool
    {
        return self::whereMobile($mobile)->value('uid') ? true : false;
    }

    /**
     * 更新用户密码
     * @param $mobile
     * @param $password
     * @return bool
     */
    public static function updatePasswordByMobile($mobile,$password) : bool
    {
        return self::whereMobile($mobile)->update(['login_pass'=>Hash::make($password)]) ? true : false;
    }

    /**
     * 判断用户是否出局
     * @param $userId
     * @return bool
     */
    public static function isOut($userId)
    {
        $isOut = self::whereUserId($userId)->value('is_out');
        return  $isOut == self::$isOutYes ? true : false;
    }

    /**
     * 判断用户是否进场
     * @param $userId
     * @return bool
     */
    public function isQueued($userId)
    {
        $isQueued = self::whereUId($userId)->value('is_queued');
        return  $isQueued == self::$isQueuedYes ? true : false;
    }


}
