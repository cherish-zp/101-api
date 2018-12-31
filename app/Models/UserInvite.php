<?php

namespace App\Models;

use Emadadly\LaravelUuid\Uuids;
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
    use Uuids;

    public $incrementing = false;
    //
    protected $table = 'user_invite';

    protected $guarded = [];

    /**
     * 更新用户邀请关系
     * @param $uuId
     */
    public static function updateUserInviteByUserId($uuid)
    {
        $maxLevel = SystemSetting::getFieldValue('recommend_get_max_level');
        static $nowLevel = 1;
        $user = User::getUserInfo(['uuid' => $uuid], ['user_id', 'invite_uid']);
        while ($user->invite_uid) {
            if ($nowLevel <= $maxLevel) {
                self::setInviteByUid($user->invite_uid, $nowLevel, $user->user_id);
                $nowLevel++;
                $inviteUser = User::getUserInfo(['user_id' => $user->invite_uid], ['uuid', 'user_id', 'invite_uid']);
                if ($inviteUser->invite_uid) {
                    self::updateUserInviteByUserId($inviteUser->uuid);
                }
            }
        }
    }

    /**
     * 设置用户邀请关系
     * @param $uuId
     * @param $level
     * @param $currentUid
     */
    public static function setInviteByUid($inviteUserId, $level, $userId)
    {
        $inviteData = self::whereUid($inviteUserId)->whereLevel($level)->first();
        if ($inviteData) {
            $newData['uids'] = $inviteData['uids'] . '|' . $userId;
            self::where('uuid', $inviteData['uuid'])->update($newData);
        } else {
            $inviteData = [
                'uid' => $inviteUserId,
                'level' => $level,
                'uids' => $userId,
            ];
            self::create($inviteData);
        }
    }
}
