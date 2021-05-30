<?php

namespace App\Listeners;

use App\Helper\ResponseMessage;
use App\Http\Controllers\VerificationController as SMS;
use App\Models\User;
use App\Models\Verification;
use App\Notifications\VerifiyAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Support\Facades\Notification;

class SendVerificationListener
{
    public function handle($event)
    {
        $code = rand(100000, 999999);

        // send sms first
        $sms = new SMS($code);
        $sms->sendCode($event->user->phone);

        // check if user has email
        if ($event->user->email) {

            try {
                //code...
                $verify = new Verification();
                $verify->code = $code;
                $verify->user_id = $event->user->id;
                $verify->verified = 0;
                $event->user->notify(new VerifiyAccount($event->user->id, $code));
                // Notification::send($event->user, new VerifiyAccount($code));
                $verify->save();
            } catch (\Throwable $th) {
                //throw $th;
                ResponseMessage::Error('Error while sending verification message', $th->getMessage());
            }
        } else {

            try {
                //code...
                $verify = new Verification();
                $verify->code = $code;
                $verify->user_id = $event->user->id;
                $verify->verified = 0;
                $verify->save();
            } catch (\Throwable $th) {
                //throw $th;
                ResponseMessage::Error('Error while sending verification message', $th->getMessage());
            }
        }
    }
}
