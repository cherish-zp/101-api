<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;


/**
 * App\Models\UserInvite
 *
 * @property string $id id
 * @property int $uid 用户id
 * @property int $level 等级
 * @property string $uids 伞下人员
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUids($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserInvite extends Base
{
    use Uuids;

    public $incrementing = false;
    //
    protected $table = 'user_invite';

    protected $guarded = [];

    static $regUserId = '';

    /**
     * 更新用户邀请关系
     * @param $id
     * @throws \Exception
     */
    public static function updateUserInviteById($id)
    {
        $maxLevel = SystemSetting::getFieldValue('recommend_get_max_level');
        static $nowLevel = 1;
        if ($nowLevel <= $maxLevel) {
            $inviteUser = [];
            $user = User::getUserInfo(['id' => $id], ['uid', 'invite_uid']);
            if ($user->invite_uid) {
                if ($nowLevel == 1) {
                    self::$regUserId = $user->uid;
                }
                self::setInviteByUid($user->invite_uid, $nowLevel, self::$regUserId);
                $inviteUser = User::getUserInfo(['uid' => $user->invite_uid], ['id', 'uid', 'invite_uid']);
                if ($inviteUser->invite_uid) {
                    $nowLevel++;
                    self::updateUserInviteById($inviteUser->id);
                }
            }

        }
    }

    /**
     * 设置用户邀请关系
     * @param $inviteUserId
     * @param $level
     * @param $uid
     * @throws \Exception
     */
    public static function setInviteByUid($inviteUserId, $level, $uid)
    {
        $inviteData = self::whereUid($inviteUserId)->whereLevel($level)->first();
        if ($inviteData) {
            $newData['uids'] = $inviteData['uids'] . '|' . $uid;
            $result = self::where('id', $inviteData['id'])->update($newData);
        } else {
            $inviteData = [
                'uid' => $inviteUserId,
                'level' => $level,
                'uids' => $uid,
            ];
            $result = self::create($inviteData);
        }
        if (!$result)
            throw new \Exception('邀请关系更新失败');
    }
}
