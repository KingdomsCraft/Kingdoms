<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:11
 */

namespace Kingdoms;

use Kingdoms\language\LanguageManager;
use Kingdoms\models\guild\Guild;
use Kingdoms\models\kingdom\Kingdom;
use pocketmine\Player;

class KingdomsPlayer extends Player {

    /** @var Kingdom|null */
    private $kingdom = null;

    /** @var int */
    private $kingdomRank = 0;

    /* Ranges */
    const KINGDOM_RANK_CITIZEN = 0;
    const KINGDOM_RANK_NOBLEMAN = 1;
    const KINGDOM_RANK_KING = 2;
    const KINGDOM_RANK_ADMIN = 3;

    /** @var Guild|null */
    private $guild = null;

    /** @var bool */
    private $admin = false;

    /**
     * Return if player rank is superior
     *
     * @param int $id
     * @return bool
     */
    public function isRankSuperior($id) {
        if($this->kingdomRank - $id >= 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Return true if a player got kingdom, false if not
     *
     * @return bool
     */
    public function gotKingdom() {
        return $this->kingdom instanceof Kingdom;
    }

    /**
     * Return true if a player got guild, false if not
     *
     * @return bool
     */
    public function gotGuild() {
        return $this->guild instanceof Kingdom;
    }

    /**
     * Return player kingdom
     *
     * @return Kingdom|null
     */
    public function getKingdom() {
        return $this->kingdom;
    }

    /**
     * Return player guild
     *
     * @return Guild|null
     */
    public function getGuild() {
        return $this->guild;
    }

    /**
     * Return if the player is admin
     *
     * @return bool
     */
    public function isAdmin() {
        return $this->admin;
    }

    /**
     * Set player kingdom
     *
     * @param Kingdom|null $kingdom
     */
    public function setKingdom($kingdom) {
        $this->kingdom = $kingdom;
    }

    /**
     * Set player kingdom rank
     *
     * @param int $rankId
     */
    public function setKingdomRank($rankId) {
        $this->kingdomRank = $rankId;
    }

    /**
     * Set player guild
     *
     * @param Guild|null $guild
     */
    public function setGuild($guild) {
        $this->guild = $guild;
    }

    /**
     * Set a player admin
     *
     * @param bool $bool
     */
    public function setAdmin($bool = true) {
        $this->admin = $bool;
    }

    /**
     * Send a message by key
     *
     * @param string $key
     */
    public function sendKingdomMessage($key) {
        $message = LanguageManager::getInstance()->getMessage($key);
        // ToDo: %coins%, %kingdom%, %guild%
        $this->sendMessage($message);
    }

    /**
     * Send a message with a kingdom position in the leaderboard
     *
     * @param $rank
     * @param string $kingdom
     * @param $points
     */
    public function sendRankedKingdom($rank, $kingdom, $points) {
        $message = LanguageManager::getInstance()->getMessage("KINGDOM_RANK");
        $message = str_replace("{rank}", $rank, $message);
        $message = str_replace("{name}", $kingdom, $message);
        $message = str_replace("{points}", $points, $message);
        $this->sendMessage($message);
    }

    /**
     * Send message with amount of pages
     *
     * @param $page
     * @param $maxPages
     */
    public function sendPageAmount($page, $maxPages) {
        $message = LanguageManager::getInstance()->getMessage("KINGDOM_TOP_PAGES");
        $message = str_replace("{page}", $page, $message);
        $message = str_replace("{maxPages}", $maxPages, $message);
        $this->sendMessage($message);
    }

}