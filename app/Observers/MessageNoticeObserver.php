<?php

namespace App\Observers;

use App\Notifications\SchoolNotice;
use App\Notifications\CollectiveNotic;
use App\Models\MessageNotic;
use XingeApp;
use Message;

class MessageNoticeObserver
{
    public function created(MessageNotic $notic)
    {
        if ($notic->scope == '全校') {
            // 通知用户有新的学校通知
            foreach ($notic->school->parents as $user) {
                $user->notify(new SchoolNotice($notic));
            }
            // XingeApp::PushAllAndroid("2100312012", "1e16401088b78a149f69ce40476532c0", "新通知", "你有新的学校通知！请及时查看");
            $push = new XingeApp("2100312012", "1e16401088b78a149f69ce40476532c0");
            $accountList = $notic->school->parents()->pluck('phone')->toArray();
            $mess = new Message();
            $mess->setExpireTime(86400);
            $mess->setTitle('新通知');
            $mess->setContent('你有新的学校通知！请及时查看');
            $mess->setType(Message::TYPE_NOTIFICATION);
            $push->PushAccountList(0, $accountList, $mess);
        } else if ($notic->scope == MessageNotic::COLLECTIVE) {
            $notic->collectives()->attach($notic->collection_ids);
            $collectives = $notic->collectives->load('parents');
            $count = 0;
            foreach ($collectives as $collective) {
                $count += count($collective->students);
                foreach ($collective->parents as $user) {
                    $user->notify(new CollectiveNotic($notic));
                }
            }
            $notic->sum_num = $count;
            $notic->save();
        }
    }
    /**
     * @param MessageNotic $notic
     */
    public function deleting(MessageNotic $notic)
    {
        $notic->collectives()->detach($notic->collection_ids);
    }
}
