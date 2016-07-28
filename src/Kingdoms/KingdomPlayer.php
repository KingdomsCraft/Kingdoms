<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 23/07/2016
 * Time: 17:57
 */

namespace Kingdoms;

use Kingdoms\models\guild\Guild;
use Kingdoms\models\kingdom\Kingdom;
use Kingdoms\models\LanguageManager;
use pocketmine\Player;

class KingdomPlayer extends Player {

    /** @var Kingdom|null */
    private $kingdom = null;

    /** @var Guild|null*/
    private $guild = null;

    /** @var int */
    private $chatRoom = 0;

    /* Chat rooms */
    const CHAT_ROOM_GLOBAL = 0;
    const CHAT_ROOM_KINGDOM = 1;

    /** @var int */
    private $kingdomRank = 0;

    /* Ranges */
    const KINGDOM_RANK_CITIZEN = 0;
    const KINGDOM_RANK_NOBLEMAN = 1;
    const KINGDOM_RANK_KING = 2;
    const KINGDOM_RANK_ADMIN = 3;

    /**
     * Return true if a player is in a kingdom, false if not.
     *
     * @return int
     */
    public function gotKingdom() {
        return ($this->kingdom instanceof Kingdom) ? 1 : 0;
    }

    /**
     * Return true if a player is in a kingdom, false if not.
     *
     * @return int
     */
    public function gotGuild() {
        return ($this->guild instanceof Guild) ? 1 : 0;
    }

    /**
     * Return player kingdom, null if player isn't in a kingdom.
     *
     * @return null|Kingdom
     */
    public function getKingdom() {
        return $this->kingdom;
    }

    /**
     * Return player guild, null if player isn't in a guild.
     *
     * @return null|Guild
     */
    public function getGuild() {
        return $this->guild;
    }

    /**
     * Return player kingdom rank.
     *
     * @return int
     */
    public function getKingdomRank() {
        return $this->kingdomRank;
    }

    /**
     * Return player chat room id.
     *
     * @return int
     */
    public function getChatRoom() {
        return $this->chatRoom;
    }

    /**
     * Return if his own rank is superior than the defined rank.
     *
     * @param int $rank
     * @return bool
     */
    public function isKingdomRankSuperior($rank) {
        $rank = $this->kingdomRank - $rank;
        if($rank >= 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Set player kingdom
     *
     * @param $value
     */
    public function setKingdom($value) {
        if($value instanceof Kingdom) {
            $this->kingdom = $value;
        }
        else {
            $this->kingdom = null;
        }
    }

    /**
     * Set player guild
     *
     * @param $value
     */
    public function setGuild($value) {
        if($value instanceof Guild) {
            $this->guild = $value;
        }
        elseif(is_array($value)) {
            $base = Base::getInstance();
            $base->getDatabaseManager()->initializeGuild($value);
            $this->guild = $base->getGuildManager()->getGuild($value);
        }
        else {
            $this->guild = null;
        }
    }

    /**
     * Set player kingdom rank
     *
     * @param int $rank
     */
    public function setKingdomRank($rank) {
        $this->kingdomRank = $rank;
    }

    /**
     * Set player chat room id
     *
     * @param string $id
     */
    public function setChatRoom($id) {
        $this->chatRoom = $id;
    }

    /**
     * Switches player chat room
     */
    public function switchChatRoom() {
        if($this->chatRoom) {
            $this->chatRoom = 0;
        }
        else {
            $this->chatRoom = 1;
        }
    }

    /**
     * Send a message from Kingdom messages
     *
     * @param string $key
     */
    public function sendKingdomMessage($key) {
        $message = LanguageManager::getInstance()->getMessage($key);
        $message = str_replace("%name%", $this->getName(), $message);
        if($this->gotKingdom()) {
            $message = str_replace("%kingdom%", $this->kingdom, $message);
        }
        $this->sendMessage($message);
    }

    /**
     * Send a message from Kingdom messages
     *
     * @param string $key
     */
    public function sendKingdomPopup($key) {
        $message = LanguageManager::getInstance()->getMessage($key);
        $message = str_replace("%name%", $this->getName(), $message);
        if($this->gotKingdom()) {
            $message = str_replace("%kingdom%", $this->kingdom, $message);
        }
        $this->sendPopup($message);
    }

    /**
     * Initialize a KingdomPlayer by database
     */
    public function initialize() {
        Base::getInstance()->getDatabaseManager()->initializePlayer($this->getName());
    }

    /**
     * Update player data in database
     */
    public function update() {
        Base::getInstance()->getDatabaseManager()->updatePlayer($this->getName());
    }

}