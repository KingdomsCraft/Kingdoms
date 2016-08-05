<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 03/08/2016
 * Time: 17:59
 */

namespace Kingdoms\command\kingdom;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use pocketmine\command\CommandSender;

class BroadcastCommand extends SubCommand implements KingdomSubCommand {

    /**
     * Execute broadcast command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->gotKingdom()) {
                if($sender->isRankSuperior(KingdomsPlayer::KINGDOM_RANK_NOBLEMAN) or $sender->isAdmin()) {
                    if(isset($args[0]) and !empty($args[0])) {
                        $message = implode(" ", $args);
                        /** @var KingdomsPlayer $player */
                        foreach($sender->getKingdom()->getPlayersByKingdom() as $player) {
                            if($player != $sender) {
                                $player->sendMessage($message);
                            }
                        }
                        $sender->sendKingdomMessage("BROADCAST_SUCCESS");
                    }
                }
                else {
                    $sender->sendKingdomMessage("BROADCAST_FAILED_BY_RANK");
                }
            }
            else {
                $sender->sendKingdomMessage("BROADCAST_FAILED_BY_KINGDOM");
            }
        }
    }

}