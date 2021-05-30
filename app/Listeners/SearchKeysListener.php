<?php

namespace App\Listeners;

use App\Helper\ResponseMessage as Resp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SearchKeysListener
{

    public function handle($event)
    {
        $searchKey = new \App\Models\SearchKeys();

        try {
            if ($searchKey::where([
                'key_word'  => $event->key,
                'product_id'  => $event->product_id
            ])->first()) {
                $searchKey::create(
                    [
                        'key_word'  => $event->key,
                        'product_id'  => $event->product_id,
                    ]
                );
            }
            return Resp::Success('ok', $searchKey);
        } catch (\Throwable $th) {
            return Resp::Error('حدث خطأ ما', $th->getMessage());
        }
    }
}
