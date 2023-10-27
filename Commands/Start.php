<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Start extends Telegram {
    public $command = '/start';
    public $description = '开始菜单';

    public function handle($message, $match = []) {
        $telegramService = $this->telegramService;
        if (!$message->is_private) return;
        $user = User::where('telegram_id', $message->user_id)->first();
        $app_url = sprintf(
            config('v2board.app_url')
        );
        if($user){
        $reply_markup =  json_encode([
                            'inline_keyboard' => [
                                [
                                    ['text' => "💰我的钱包", 'callback_data' => '/mywallet'], ['text' => "🎫流量查询", 'callback_data' => '/traffic']
                                ],
                                [
                                    ['text' => "📖订阅链接", 'callback_data' => '/sublink'],['text' => "📝我的订阅", 'callback_data' => '/mysubscribe']
                                ],
                                [
                                    ['text' => "🏠购买套餐", 'callback_data' => '/store'],
                                 ],
                                [
                                    ['text' => "💲邀请返利", 'callback_data' => '/invite'],['text' => "💁最新官网", 'url' => $app_url]
                                ],
                                [
                                    ['text' => "🌟在线客服", 'callback_data' => '/kefu'],
                                 ]
                            ]
                        ]);
        }else{
        $reply_markup =  json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "buy", 'callback_data' => '/store'],
                        ],
                        [
                            ['text' => "🆓تست رایگان🆓", 'callback_data' => '/bind'],
                        ],
                        [
                            ['text' => "💵زیر مجموعه گیری💵", 'url' => $app_url],
                        ],
                        [
                            ['text' => "🗑حذف سرویس🗑", 'url' => $app_url],
                        ],
                        [
                            ['text' => "💡راهنمای اتصال و پرداخت💡", 'url' => $app_url],
                        ],
                        [
                            ['text' => "🆘چنل اطلاع رسانی🆘", 'url' => $app_url],
                        ],
                        [
                            ['text' => "🆘پشتیبانی🆘", 'url' => $app_url],
                        ],
                    ],
                    'resize_keyboard' => true,
                ]); 
        }
       $text = sprintf(
            "💫سلام خوش آمدید💫",
            config('v2board.app_name', 'V2Board'),
            config('v2board.app_description')
        );
        if(isset($message->callback_query_id)){
        $telegramService->editMessageText($message->chat_id,$message->message_id, $text, $reply_markup);     
        }else{
        $telegramService->sendMessageMarkup($message->chat_id, $text, $reply_markup);
        }
    }
}
