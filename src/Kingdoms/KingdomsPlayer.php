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

    /** @var Guild|null */
    private $guild = null;

    /** @var bool */
    private $leader = false;

    /** @var bool */
    private $admin = false;

    /**
     * Return player data
     *
     * @return array
     */
    public function getPlayerData() {
        $kingdom = ($this->gotKingdom()) ? $this->getKingdom()->getName() : "No kingdom";
        $guild = ($this->gotGuild()) ? $this->getGuild()->getName() : "No guild";
        return [
            "kingdom" => $kingdom,
            "guild" => $guild
        ];
    }

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
     * Return player kingdom rank
     *
     * @return int
     */
    public function getKingdomRank() {
        return $this->kingdomRank;
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
     * Return if the player is leader (referring to the guild)
     *
     * @return bool
     */
    public function isLeader() {
        return $this->leader;
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
     * Set a player leader of his guild
     *
     * @param bool $bool
     */
    public function setLeader($bool = true) {
        $this->leader = $bool;
    }

    /**
     * Send a message by key
     *
     * @param string $key
     */
    public function sendKingdomMessage($key) {
        $message = LanguageManager::getInstance()->getMessage($key);
        if($this->gotKingdom()) {
            $message = str_replace("%kingdom%", $this->getKingdom()->getName(), $message);
        }
        if($this->gotGuild()) {
            $message = str_replace("%guild%", $this->getGuild()->getName(), $message);
        }
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

    /**
     * Send kingdom info
     *
     * @param $name
     * @param $motto
     * @param $points
     * @param $leader
     * @param $warsWon
     * @param $rank
     * @param $citizens
     */
    public function sendKingdomInfo($name, $motto, $points, $leader, $warsWon, $rank, $citizens) {
        $message = LanguageManager::getInstance()->getMessage("KINGDOM_INFO_HEADER");
        $message = str_replace("{name}", $name, $message);
        $this->sendMessage($message);
        $message = LanguageManager::getInstance()->getMessage("KINGDOM_INFO");
        $message = str_replace("{name}", $name, $message);
        $message = str_replace("{motto}", $motto, $message);
        $message = str_replace("{points}", $points, $message);
        $message = str_replace("{leader}", $leader, $message);
        $message = str_replace("{warsWon}", $warsWon, $message);
        $message = str_replace("{rank}", $rank, $message);
        $message = str_replace("{citizens}", $citizens, $message);
        $this->sendMessage($message);
    }

    /**
     * Update the player
     */
    public function update() {
        Main::getInstance()->getPluginDatabase()->getPlayerDatabase()->updatePlayer($this->getName());
    }

}