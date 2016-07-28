<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 25/07/2016
 * Time: 12:39
 */

namespace Kingdoms\command;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use Kingdoms\models\kingdom\Kingdom;
use Kingdoms\models\LanguageManager;
use kingdomscraft\economy\Economy;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;

class KingdomCommand extends Command {

    /** @var Base */
    private $plugin;

    /**
     * KingdomCommand constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        parent::__construct("kingdom", "Main Kingdoms command", "Usage: /kingdom help", ["k"]);
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if($sender instanceof KingdomPlayer) {
            if(isset($args[0])) {
                $kingdomManager = $this->plugin->getKingdomManager();
                $economy = Economy::getInstance();
                $config = $this->plugin->getConfig()->getAll();
                $database = $this->plugin->getDatabaseManager();
                switch(strtolower($args[0])) {
                    case "help":
                        $header = LanguageManager::getInstance()->getMessage("HELP_HEADER");
                        if(isset($args[1]) and (((int)$args[1]) > 0) and (((int)$args[1]) < 5 /*ToDo: number of pages*/)) {
                            $header = str_replace("{NUMBER}", $args[1], $header);
                            $number = (int)$args[1];
                        }
                        else {
                            $number = "1";
                            $header = str_replace("{NUMBER}", "1", $header);
                            $sender->sendMessage($header);
                        }
                        $sender->sendMessage($header);
                        $sender->sendKingdomMessage("HELP_PAGE_{$number}");
                        break;
                    case "create":
                        if($sender->isKingdomRankSuperior(KingdomPlayer::KINGDOM_RANK_ADMIN or $sender->isOp())) {
                            if(isset($args[1])) {
                                if($kingdomManager->getKingdom($args[1]) instanceof Kingdom) {
                                    $sender->sendKingdomMessage("CREATE_FAILED_KINGDOM_EXISTS");
                                }
                                else {
                                    if(isset($args[2])) {
                                        $motd = $args;
                                        unset($motd[0], $motd[1]);
                                    }
                                    else {
                                        $motd = "This is my kingdom!";
                                    }
                                    $database->createKingdom($args[1], $motd);
                                    $sender->sendKingdomMessage("CREATE_KINGDOM_SUCCESS");
                                }
                            }
                            else {
                                $sender->sendKingdomMessage("CREATE_USAGE");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("CREATE_KINGDOM_FAILED_REASON_RANK");
                        }
                        break;
                    case "join":
                        if(isset($args[1])) {
                            $kingdom = $kingdomManager->getKingdom($args[1]);
                            if($kingdom instanceof Kingdom) {
                                if($economy->getRubies($sender) >= (int)$config["join-price"]) {
                                    $economy->removeRubies($sender, (int)$config["join-price"]);
                                    $sender->setKingdom($kingdom);
                                    $sender->sendKingdomMessage("JOIN_SUCCESS");
                                }
                                else {
                                    $sender->sendKingdomMessage("JOIN_FAILED_REASON_MONEY");
                                }
                            }
                            else {
                                $sender->sendKingdomMessage("JOIN_FAILED_REASON_KINGDOM");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("JOIN_USAGE");
                        }
                        break;
                    case "home":
                        if($sender->gotKingdom()) {
                            $home = $sender->getKingdom()->getHome();
                            if($home instanceof Position) {
                                $sender->teleport($home);
                                $sender->sendKingdomMessage("HOME_SUCCESS");
                            }
                            else {
                                $sender->sendKingdomMessage("HOME_FAILED_REASON_POSITION");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("HOME_FAILED_REASON_KINGDOM");
                        }
                        break;
                    case "leave":
                        if($sender->gotKingdom()) {
                            if($economy->getRubies($sender) >= (int)$config["leave-price"]) {
                                $economy->removeRubies($sender, (int)$config["leave-price"]);
                                $sender->setChatRoom(0);
                                $sender->setKingdom(null);
                                $sender->sendKingdomMessage("LEAVE_SUCCESS");
                            }
                            else {
                                $sender->sendKingdomMessage("LEAVE_FAILED_REASON_MONEY");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("LEAVE_FAILED_REASON_KINGDOM");
                        }
                        break;
                    case "list":
                        $sender->sendKingdomMessage("LIST_HEADER");
                        $message = LanguageManager::getInstance()->getMessage("LIST_COMMAND");
                        foreach($kingdomManager->getKingdoms() as $kingdom) {
                            $message = str_replace("{kingdom}", $kingdom->getName(), $message);
                            $message = str_replace("{amount}", count($kingdom->getPlayersByKingdom()), $message);
                            $sender->sendMessage($message);
                        }
                        break;
                    case "chat":
                        if($sender->getChatRoom() == 1) {
                            $sender->switchChatRoom();
                            $sender->sendKingdomMessage("CHAT_TO_GLOBAL");
                        }
                        else {
                            if($sender->gotKingdom()) {
                                $sender->switchChatRoom();
                                $sender->sendKingdomMessage("CHAT_TO_KINGDOM");
                            }
                            else {
                                $sender->sendKingdomMessage("CHAT_FAILED_REASON_KINGDOM");
                            }
                        }
                        break;
                    case "topguilds":
                        $page = 1;
                        if($sender->gotKingdom()) {
                            if(isset($args[1]) and intval($args[1]) > 0) {
                                $page = (int)$args[1];
                            }
                            $database->sendTopGuilds($sender, $sender->getKingdom(), $page);
                            $sender->sendKingdomMessage("TOPGUILDS_PRE-RANK");
                        }
                        else {
                            $sender->sendKingdomMessage("TOPGUILDS_FAILED_REASON_KINGDOM");
                        }
                        break;
                    case "war":
                        break;
                    case "heal":
                        if($sender->gotKingdom()) {
                            if($sender->isKingdomRankSuperior(KingdomPlayer::KINGDOM_RANK_KING)) {
                                foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
                                    $player->setHealth($player->getMaxHealth());
                                }
                                $sender->sendKingdomMessage("HEAL_SUCCESS");
                            }
                            else {
                                $sender->sendKingdomMessage("HEAL_FAILED_REASON_RANK");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("HEAL_FAILED_REASON_KINGDOM");
                        }
                        break;
                    case "sethome":
                        if($sender->gotKingdom()) {
                            if($sender->isKingdomRankSuperior(KingdomPlayer::KINGDOM_RANK_NOBLEMAN)) {
                                if($sender->getLevel()->getName() == $config["home"]) {
                                    $sender->getKingdom()->setHomePosition($sender->getPosition());
                                    $sender->sendKingdomMessage("SETHOME_SUCCESS");
                                }
                                else {
                                    $sender->sendKingdomMessage("SETHOME_FAILED_REASON_MAP");
                                }
                            }
                            else {
                                $sender->sendKingdomMessage("SETHOME_FAILED_REASON_RANK");
                            }
                        }
                        else {
                            $sender->sendKingdomMessage("SETHOME_FAILED_REASON_KINGDOM");
                        }
                        break;
                }
            }
            else {
                $sender->sendKingdomMessage("KINGDOM_LEARN");
            }
        }
        else {
            // ToDo: add some commands support
            $sender->sendMessage("Please, run this command in-game!");
        }
    }

}