<?php

namespace App\Plugins\Telegram\Commands;

use App\Models\User;
use App\Plugins\Telegram\Telegram;
use App\Utils\Helper;

class Store extends Telegram {
    public $command = '/store';
    public $description = 'buy config';

    public function handle($message, $match = []) {
        $telegramService = $this->telegramService;
        if (!$message->is_private) return;

        // Code to display sales plans
        $plans = [
            ['name' => 'Plan 1', 'price' => '10 dollars'],
            ['name' => 'Plan 2', 'price' => '20 dollars'],
            ['name' => 'Plan 3', 'price' => '30 dollars'],
        ];

        $reply_markup = json_encode([
            'inline_keyboard' => array_map(function ($plan) {
                return [
                    ['text' => $plan['name'], 'callback_data' => '/buy_plan_' . $plan['name']]
                ];
            }, $plans)
        ]);

        $text = "Please select one of the following plans:";

        $telegramService->sendMessageMarkup($message->chat_id, $text, $reply_markup);
    }

    public function handleCallbackQuery($callback_query) {
    $telegramService = $this->telegramService;
    $message = $callback_query->message;
    $planName = str_replace('/buy_plan_', '', $callback_query->data);

    $text = "Please enter your name:";
    $reply_markup = json_encode(['force_reply' => true]);

    $telegramService->sendMessage($message->chat->id, $text, ['reply_markup' => $reply_markup]);
    $telegramService->answerCallbackQuery($callback_query->id);

    // Save the plan name and perform related operations in the database or other actions
    // ...
    }

    public function handleMessage($message) {
        $telegramService = $this->telegramService;
        $text = $message->text;

        // Check if the message is a reply to the previous message
        if (isset($message->reply_to_message) && $message->reply_to_message->text === 'Please enter your name:') {
            $planName = str_replace('/buy_plan_', '', $message->reply_to_message->reply_markup->inline_keyboard[0][0]->callback_data);

            // Save the plan name and perform related operations in the database or other actions
            // ...

            $replyText = "Plan $planName successfully purchased.";
            $telegramService->sendMessage($message->chat->id, $replyText);
        }
    }
}