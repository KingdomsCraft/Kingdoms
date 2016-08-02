<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 21:48
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\language\LanguageManager;

class ListCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute list command
     *
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function execute(KingdomsPlayer $sender, $args) {
        $sender->sendKingdomMessage("LIST_HEADER");
        foreach($this->getPlugin()->getKingdomManager()->getKingdoms() as $kingdom) {
            $message = LanguageManager::getInstance()->getMessage("LIST_MESSAGE");
            $message = str_replace("{kingdom}", $kingdom->getName(), $message);
            $message = str_replace("{online}", count($kingdom->getPlayersByKingdom()), $message);
            $sender->sendMessage($message);
        }
    }

}