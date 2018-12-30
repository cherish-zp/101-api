<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserInvite
 *
 * @property string $uuid uuid
 * @property int $uid 用户id
 * @property int $level 等级
 * @property string $uids 伞下人员
 * @property \Illuminate\Support\Carbon $created_at 创建时间
 * @property \Illuminate\Support\Carbon $updated_at 更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUids($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserInvite whereUuid($value)
 * @mixin \Eloquent
 */
class UserInvite extends Base
{
    //
    protected $table = 'user_invite';


    /**
     * 更新用户邀请关系
     * @param $userId
     */
    public static function updateUserInviteByUserId($userId)
    {
        $maxLevel = SystemSetting::getFieldValue('recommend_get_max_level');
        $nowLevel = 1;
        $inviteUserId = User::getInviteUserIdByUid($userId);
        while ($inviteUserId) {
            if ($nowLevel <= $maxLevel) {
                self::setInviteByUid($inviteUserId, $nowLevel, $userId);
                $nowLevel++;
                self::updateUserInviteByUserId($inviteUserId);
            }
        }

    }

    /**
     * 设置用户邀请关系
     * @param $uid
     * @param $level
     * @param $currentUid
     */
    public static function setInviteByUid($uid, $level, $currentUid)
    {
        $inviteData = self::whereUid($uid)->whereLevel($level)->first();
        $newData['uids'] = $inviteData['uids'] . '|' . $currentUid;
        self::where('uuid', $inviteData['uuid'])->update($newData);
    }
}
