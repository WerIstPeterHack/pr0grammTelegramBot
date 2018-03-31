# pr0grammTelegramBot
A Telegram-Bot that allows people to search the German image board pr0gramm.com for astonishing uploads and send them directly to the chat. The user can define several search terms as an inline query and the bot will respond to it with a list of results. The user can select a result for preview or directly send it to the chat. 

The bot uses the great PHP-Telegram-Bot-Library from Longman: https://github.com/php-telegram-bot

# Installation

Use composer to install dependencies. 

*Important*

If you want to use Botan for analysing the traffic and in order to protect your users privacy, you need to make changes to the Botan-Class. See the anonymize-paragraph below!

# Set up the bot

You need a server with https and a public reachable domain, for example: 

    https://myfancybot.com

Open your Telegram-client (desktop or smartphone will do it) and start the @BotFather. Create a new bot with

    /newbot

The 'name' of the bot is for internal use only. The 'username' is how the user will address the bot, it has to end with 'bot', for example foobarBot. Your reward will be a token like this:

    123123123:AHJASHDKJASDHASHDJASDAD

In the bot-settings areas you may make sure, that the inline mode is enabled and groups are allowed. Anything else can remain unchanged. 

Now open the manager.php and add the following information (i'll just quote the particular lines):

    $bot_username = 'foobarBot'; // Without "@"
    [...]
    'api_key' => '123123123:AHJASHDKJASDHASHDJASDAD',
    [...]
    'secret'       => 'aSecretPassword',
    [...]
    'url' => 'https://myfancybot.com/manager.php',

Basically, that's all. You may also modify / uncomment the following parameters:

    'validate_request' => FALSE, // this is to validate the IP-Address, if you call the manager.php in the browser
    
    'token' => '123123123', // IF you want to use statistics, enter your Botan-token here, You also may just comment it out

# Activate the bot

You are almost done. Now call the manager.php in the browser and add two parameters a=set and your secret with s=aSecretPassword:

    https://myfancybot.com/manager.php?s=aSecretPassword&a=set
    
The webhook will be installed. You always can check it's status with:

    https://myfancybot.com/manager.php?s=aSecretPassword&a=webhookinfo

# Anonymize 
The Botan-implementation usually collects usernames and userids and send them to Yandex. You can disable Botan at all or if you want to anonymize usage, make the following modifications to Botan.php. You may use this edits to create a fork. This is just a quick'n'dirty workaround.

Look for the public static function track() and the line

		$uid = isset($data['from']['id']) ? $data['from']['id'] : 0;

Uncomment this line and after it add the following:

		$uid = 0;
				
		$data['from']['id'] = 0;
		$data['from']['first_name'] = 'anon';
		$data['chat']['id'] = 0;
		$data['chat']['first_name'] = 'anon';
		$data['text'] = NULL;
        
That's all.

# Bot features

Currently, the bot only supports the SFW-area, because anything else requires a login-token. After connecting to the bot and running the initial /start-command, the user will receive a welcome notice. The /help-command describe the basic usage (in German). The following will search the Pr0gramm for the terms for hello world and result a grid of several images:

     @prgrmmBot hello world

If you particulary want to search for videos, you need to add +webm:

     @prgrmmBot hello world
     
The result is a list of videos. The bot will send the selected item to the current chat, containing information about the uploader and the the vote-statistics. With clicking the image, the user will be redirected to the original post at pr0gramm.com
