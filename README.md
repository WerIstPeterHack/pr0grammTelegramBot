# pr0grammTelegramBot
A Telegram-Bot that allows people to search the German image board pr0gramm.com for astonishing uploads and send them directly to the chat. 

# Installation

Use composer to install dependencies. 

*Important*

If you want to use Botan an in order to protect your users privacy, you need to make changes to the Botan-Class. See the anonymize-paragraph below!

# Set up the bot

You need a server with https and a public reachable domain, for example: 

    https://myfancybot.com

Open your Telegram-client (desktop or smartphone will do it) and start the @BotFather. Create a new bot with

    /newbot

The 'name' of the bot is for internal use only. The 'username' is how the user will address the bot, it has to end with 'bot', for example foobarBot. Your reward will be a token like this:

    123123123:AHJASHDKJASDHASHDJASDAD

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

Look for the public static function track() and add
		// ANONYMIZING STATS
		if (isset($update->inline_query)) {

			if (isset($update->inline_query['from'])) {

				if (isset($update->inline_query['from']['id'])) {
				
					$update->inline_query['from']['id'] = '0';
					$update->inline_query['from']['first_name'] = 'anon';
					$update->raw_data['inline_query']['from']['id'] = '0';
					$update->raw_data['inline_query']['from']['first_name'] = 'anon';
						
				}
			}
		}
    
after the inital error handling. Next, in the same function, look for the variable $uid and change from
		$uid = isset($data['from']['id']) ? $data['from']['id'] : 0;
to
		$uid = 0;
    
That's all.
