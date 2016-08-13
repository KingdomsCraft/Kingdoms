<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 06/08/2016
 * Time: 12:16
 */

namespace Kingdoms\command\guild;

use Kingdoms\command\SubCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\models\Invitation;
use pocketmine\command\CommandSender;

class AcceptCommand extends SubCommand implements GuildSubCommand {

    /**
     * Execute accept command
     *
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args) {
        if($sender instanceof KingdomsPlayer) {
            if($sender->gotGuild()) {
                $sender->sendKingdomMessage("GUILD_ACCEPT_FAILED_BY_GUILD");
            }
            else {
                if(isset($args[0]) and !empty($args[0])) {
                    if($this->getPlugin()->getGuildManager()->isGuild($args[0])) {
                        $guild = $this->getPlugin()->getGuildManager()->getGuild($args[0]);
                        if($sender->isInvitation($guild)) {
                            $sender->getInvitation($guild)->accept();
                        }
                        else {
                            $sender->sendKingdomMessage("GUILD_INVITATION_FAILED_BY_NO_EXISTS");
                        }
                    }
                    else {
                        $sender->sendKingdomMessage("GUILD_INVITATION_FAILED_BY_NO_EXISTS");
                    }
                }
                else {
                    if($sender->getLastInvitation() instanceof Invitation) {
                        $sender->getLastInvitation()->accept();
                    }
                    else {
                        $sender->sendKingdomMessage("GUILD_ACCEPT_FAILED_BY_NO_INVITATIONS");
                    }
                }
            }
        }
        else {
            $sender->sendMessage("Please, run this command in game!");
        }
    }

}