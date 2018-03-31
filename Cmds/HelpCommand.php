<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 *
 * Help a user how to use the bot
 */
class HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'How to use the bot';

    /**
     * @var string
     */
    protected $usage = '/help';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
		// $text    = $message->getText(true);

        $sender = $message->from['first_name'];
	
		$text[] = 'Dieser Bot durchsucht den SFW-Bereich von https://pr0gramm.com.'; 
		$text[] = 'Gib meinen Namen ein (@prgrmmBot), gefolgt von einem oder vielen Suchbegriffen.';
		$text[] = 'Für gewöhnlich liefere ich als Ergebnis nur Bilder zurück.';
		$text[] = 'Wenn du nach Videos suchst, ergänze deinen Suchbegriff mit einem +webm.';
				
        $data = [
            'chat_id' => $chat_id,
            'text'    => implode(PHP_EOL, $text),
        ];

        return Request::sendMessage($data);
    }
}
