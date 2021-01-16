<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use Illuminate\Support\Facades\Cache;
use App\Model\Member;
use App\User;

/**
 * Class HelpCommand.
 */
class DebugCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'debug';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['deb'];

    /**
     * @var string Command Description
     */
    protected $description = 'Bot Debug Commands';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $response = $this->getUpdate();
        $chat_id = $response->getMessage()->getChat()->getId();
        $whitelist = [1049542881, 365874331, -418820766];
        $textContent = $response->getMessage()->getText();

        $modelMember = new Member;

        $params = explode(' ', $textContent);
        if (!isset($params[1])) {
            $text = 'Perintah tidak lengkap! (param1)';
            $this->replyWithMessage(compact('text'));
            return;
        }

        if ($params[1] == 'chat_id') {
            $text = $chat_id;
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
            return;
        } else {
            $text = 'Perintah salah, periksa kembali';
            $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
            return;
        }
    }
}
