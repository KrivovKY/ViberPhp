<?php

require_once("../vendor/autoload.php");

use Viber\Bot;
use Viber\Api\Sender;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$config = require('./config.php');
$apiKey = $config['apiKey'];

// так будет выглядеть наш бот (имя и аватар - можно менять)
$botSender = new Sender([
    'name' => 'SKILS bot',
    'avatar' => 'https://demo.suprotec.by//skils.jpg',
]);

// log bot interaction
$log = new Logger('bot');
$log->pushHandler(new StreamHandler('bot.log'));

$bot = null;

try {
    $bot = new Bot(['token' => $apiKey]);
    $bot
    // клиент подписался на публичный эккаунт
    ->onSubscribe(function ($event) use ($bot, $botSender, $log) {
        $log->info('onSubscribe handler');
        $this->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText('Спасибо что подписались на наш эккаунт!')
        );
    })
    ->onConversation(function ($event) use ($bot, $botSender, $log) {
		$log->info('onConversation handler');
        // клиент переходит в чат
        // можно отправить "привествие", но нельзя посылать более сообщений
            $buttons = [];
            for ($i = 0; $i <= 3; $i++) {
                $buttons[] =
                    (new \Viber\Api\Keyboard\Button())
                        ->setColumns(1)
                        ->setActionType('reply')
                        ->setActionBody('k' . $i)
                        ->setText('k' . $i);
            }
            return (new \Viber\Api\Message\Text())
                ->setSender($botSender)
                ->setText("Привет! Чем я могу Вам помочь? Выберите один из вариантов")
                ->setKeyboard(
                    (new \Viber\Api\Keyboard())
                        ->setButtons($buttons)
                );
    })
    ->onText('|whois .*|si', function ($event) use ($bot, $botSender, $log) {
        // это событие будет вызвано если пользователь пошлет сообщение 
        // которое совпадет с регулярным выражением
        $bot->getClient()->sendMessage(
            (new \Viber\Api\Message\Text())
            ->setSender($botSender)
            ->setReceiver($event->getSender()->getId())
            ->setText("I do not know )")
        );
    })
    ->onText('|k\d+|is', function ($event) use ($bot, $botSender, $log) {
        $caseNumber = (int)preg_replace('|[^0-9]|s', '', $event->getMessage()->getText());
        $log->info('onText demo handler #' . $caseNumber);
        $client = $bot->getClient();
        $receiverId = $event->getSender()->getId();
        switch ($caseNumber) {
            case 0:
                $client->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText('Basic keyboard layout')
                        ->setKeyboard(
                            (new \Viber\Api\Keyboard())
                                ->setButtons([
                                    (new \Viber\Api\Keyboard\Button())
                                        ->setActionType('reply')
                                        ->setActionBody('btn-click')
                                        ->setText('Tap this button')
                                ])
                        )
                );
                break;
            //
			case 1:
                $client->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText('Текст сообщения 1')
                );
                break;
            //
			case 2:
                $client->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText('Текст сообщения 1')
                );
                break;
            //
			case 3:
                $client->sendMessage(
                    (new \Viber\Api\Message\Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText('Текст сообщения 1')
                );
                break;
            //
        }
    })
    ->run();
} catch (Exception $e) {
   $log->warn('Exception: ' . $e->getMessage());
    if ($bot) {
        $log->warn('Actual sign: ' . $bot->getSignHeaderValue());
        $log->warn('Actual body: ' . $bot->getInputBody());
    }
}