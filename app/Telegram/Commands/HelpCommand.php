<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'Get Introduction and Instruction';

    protected $pattern = '{topic}';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $response = $this->getUpdate();
        $args = $this->getArguments();

        $text = 'Halo, ini adalah layanan Bot Lumbung Network.' . chr(10) . chr(10);

        if (!empty($args)) {
            if ($args['topic'] == 'kbb') {
                $text .= 'Gunakan format: ' . chr(10);
                $text .= '"/kbb {parameter} {username}"' . chr(10);
                $text .= 'Contoh: /kbb status Budi001' . chr(10) . chr(10);
                $text .= 'Parameter yang tersedia:' . chr(10);
                $text .= '"status" dan "bonus".' . chr(10) . chr(10);
                $text .= 'Parameter "status" akan menampilkan informasi Status keanggotaan KBB, Masa Aktif dan Peringkat.' . chr(10);
                $text .= 'Parameter "bonus" akan menampilkan informasi berkaitan dengan perolehan Bonus akun tersebut.' . chr(10);
            }
        }

        $this->replyWithMessage(compact('text'));
    }
}
