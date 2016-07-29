<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:11
 */

namespace Kingdoms;

use Kingdoms\models\guild\Guild;
use Kingdoms\models\kingdom\Kingdom;
use pocketmine\Player;

class KingdomPlayer extends Player {

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

}