<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultPhoto;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultVideo;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Request;

/**
 * Inline query command
 *
 * Command that handles inline queries.
 */
 
class InlinequeryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'inlinequery';

    /**
     * @var string
     */
    protected $description = 'Reply to inline query';

    /**
     * @var string
     */
    protected $version = '1.1.1';


    /**
     * @var string
     */
    protected $apiUrl = 'https://app.pr0gramm.com/api/categories/v1/general?flags=9&tags=+';


    /**
     * @var string
     */
    protected $domain = 'pr0gramm.com/';

    /**
     * @var string
     */
    protected $protocol = 'https://';

    /**
     * @var string
     */
    protected $destinationUrl = 'https://www.pr0gramm.com/new/';

    /**
     * @var integer
     */
    protected $limitResult = 50;

    /**
     * @var string
     */
    protected $apiResult = NULL;

    /**
     * @var string
     */
    protected $inlineQueryResult = array();

	/**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
						
        $inline_query = $this->getInlineQuery();
		
        $this->query = $inline_query->getQuery();

        $data = ['inline_query_id' => $inline_query->getId()];

        if ($this->query !== '') {
			
			
			$this->setApiResult();
	
			$this->setInlineQueryResult();

	
        }

        $data['results'] = '[' . implode(',', $this->inlineQueryResult) . ']';

        return Request::answerInlineQuery($data);
    }
	
	/**
     * Command setApiResult method
     *
     */
    private function setApiResult()
    {
		$queryString = str_replace(' ', '+', $this->query);
				
		if (strpos($queryString, '+webm') === FALSE) {
			
			$queryString .= '-webm';
			
		}
		
		$this->apiResult = json_decode(file_get_contents($this->apiUrl.$queryString));


	}
	
	/**
     * Command setInlineQueryResult method
     *
     */
    private function setInlineQueryResult()
    {
	
		foreach ($this->apiResult->items as $item) {
			
			$thumbFileName = basename($item->thumb);
			$thumbFileType = pathinfo($thumbFileName, PATHINFO_EXTENSION);

			$imageFileName = basename($item->image);
			$imageFileType = pathinfo($imageFileName, PATHINFO_EXTENSION);

			if ($thumbFileType == 'jpg') {
					
				if ($imageFileType == 'jpg') {
					
					$this->inlineQueryResult[] = new InlineQueryResultPhoto(array(
						'id'                    =>  sizeof($this->inlineQueryResult),
						'title'          		=> 'Tag: ' . $this->query,
						'description'           => 'Votes: ' . $item->up . ' / ' . $item->down,
						'thumb_url'             => $this->protocol.'img.'.$this->domain.$item->thumb,
						'photo_url'             => $this->protocol.'img.'.$this->domain.$item->image,
						'caption'             	=> 'Hochgeladen am: '. date ('d.m.Y', $item->created).
							' von '. $item->user.PHP_EOL.
							' Bewertung +'.$item->up .' / -'.$item->down.PHP_EOL.
							$this->destinationUrl.$item->id
						// 'input_message_content' => new InputTextMessageContent([
							// 'message_text' => '<a href="' . $this->destinationUrl.$item->id . '"/>'
							// ])
					) );

				} else if ($imageFileType == 'mp4') {
															
					$this->inlineQueryResult[] = new InlineQueryResultVideo(array(
						'id'                    =>  sizeof($this->inlineQueryResult),
						'title'          		=> 'Tag: ' . $this->query,
						'description'           => 'Votes: ' . $item->up . ' / ' . $item->down,
						'mime_type'           	=> 'video/mp4',
						'thumb_url'             => $this->protocol.'thumb.'.$this->domain.$item->thumb,
						'video_url'             => $this->protocol.'vid.'.$this->domain.$item->image,
						'caption'             	=> 'Hochgeladen am: '. date ('d.m.Y', $item->created).
							' von '. $item->user.PHP_EOL.
							' Bewertung +'.$item->up .' / -'.$item->down.PHP_EOL.
							$this->destinationUrl.$item->id
							// 'input_message_content' => new InputTextMessageContent([
							// 'message_text' => '<a href="' . $this->destinationUrl.$item->id . '"/>'
							// ])
					));

				}

			}
			
			if (sizeof($this->inlineQueryResult) >= $this->limitResult) {
				
				break;
			}
				
		}

	}
	
}
