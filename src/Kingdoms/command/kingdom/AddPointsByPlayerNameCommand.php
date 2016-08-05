<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 7:09
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\language\LanguageManager;
use pocketmine\command\CommandSender;

class AddPointsByPlayerNameCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Send a message by key
     *
     * @param CommandSender $sender
     * @param $key
     */
    public function sendMessage(CommandSender $sender, $key) {
        $sender->sendMessage(LanguageManager::getInstance()->getMessage($key));
    }

    /**
     * Execute addpointsbyplayername command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if(isset($args[0]) and !empty($args[0])) {
            $amount = (int) $args[0];
            if(is_int($amount)) {
                if(isset($args[1])) {
                    $this->getPlugin()->getPluginDatabase()->getKingdomDatabase()->addCoinsByPlayerName($args[1], $amount);
                    $this->sendMessage($sender, "KINGDOM_ADDPOINTSBYPLAYERNAME_SUCCESS");
                }
                else {
                    $this->sendMessage($sender, "KINGDOM_ADDPOINTSBYPLAYERNAME_USAGE");
                }
            }
            else {
                $this->sendMessage($sender, "KINGDOM_ADDPOINTSBYPLAYERNAME_FAILED_BY_NUMBER");
            }
        }
        else {
            $this->sendMessage($sender, "KINGDOM_ADDPOINTSBYPLAYERNAME_USAGE");
        }
    }

}