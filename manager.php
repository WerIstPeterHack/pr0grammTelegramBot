<?php
ini_set('display_errors', 1);

/**
 * README
 * This configuration file is intended to be used as the main script for the PHP Telegram Bot Manager.
 * Uncommented parameters must be filled
 *
 * For the full list of options, go to:
 * https://github.com/php-telegram-bot/telegram-bot-manager#set-extra-bot-parameters
 */

// Load composer
require_once __DIR__ . '/vendor/autoload.php';

// bot username
$bot_username = 'myFancyBot'; // Without "@"

try {
		$bot = new TelegramBot\TelegramBotManager\BotManager([
        // API token of the bot
        'api_key'      => '123123123:ABCABCABCABCABCABCABCABCABC',
        'bot_username' => $bot_username,

        // secret to be used (manager.php?s=...) when calling this script
        'secret'       => 'ABC123ABC123ABC123ABC123ABC123',

        'webhook'      => [
           'url' => 'https://myFancyBot.com/manager.php',
           // Limit maximum number of connections
           'max_connections' => 10
        ],

		'validate_request' => FALSE, // validate IP-Address
        'commands' => [
           // Files in this folder will be registered as bot commands
           'paths'   => [
               __DIR__ . '/Cmds'
           ]
	   ],

        // if you need bot to use logging, uncomment those lines
        // 'logging'  => [
           // 'debug'  => __DIR__ . "/{$bot_username}_debug.log",
           // 'error'  => __DIR__ . "/{$bot_username}_error.log",
           // 'update' => __DIR__ . "/{$bot_username}_update.log"
        // ],
        // Botan.io integration for analytics with yandex, see Readme concering anonymisation
        // 'botan' => [
           // 'token' => 'ABC123ABC123ABC123ABC123ABC123ABC123',
        // ],

        // Requests Limiter (tries to prevent reaching Telegram API limits)
        'limiter'      => ['enabled' => true]
    ]);

    // Run the bot
    $bot->run();

// if you need bot to use logging, uncomment those lines
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Log telegram errors
    // Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // echo $e;
}
