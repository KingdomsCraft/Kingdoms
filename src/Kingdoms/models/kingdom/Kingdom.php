<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 15:12
 */

namespace Kingdoms\models\kingdom;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use pocketmine\level\Level;
use pocketmine\level\Position;

class Kingdom {

    /** @var Base */
    private $plugin;

    /** @var string */
    private $name;

    /** @var int */
    private $points;

    /** @var string */
    private $motto;

    /** @var int */
    private $lostWars;

    /** @var int */
    private $wonWars;

    /** @var string */
    private $home;

    /**
     * Kingdom constructor.
     * @param Base $plugin
     * @param string $name
     * @param int $points
     * @param string $motto
     * @param int $lostWars
     * @param int $wonWars
     * @param string $home
     */
    public function __construct(Base $plugin, $name, $points, $motto, $lostWars, $wonWars, $home) {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->points = $points;
        $this->motto = $motto;
        $this->lostWars = $lostWars;
        $this->wonWars = $wonWars;
        $this->home = $home;
    }

    /**
     * Return kingdom name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Return kingdom points
     *
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Return kingdom lost wars
     *
     * @return int
     */
    public function getLostWars() {
        return $this->lostWars;
    }

    /**
     * Return kingdom motto
     *
     * @return string
     */
    public function getMotto() {
        return $this->motto;
    }

    /**
     * Return kingdom won wars
     *
     * @return int
     */
    public function getWonWars() {
        return $this->wonWars;
    }

    /**
     * Return kingdom home (Not parsed)
     *
     * @return string
     */
    public function getHome() {
        return $this->home;
    }

    /**
     * Return kingdom home (parsed)
     *
     * @return null|Position
     */
    public function getHomePosition() {
        $pos = explode(",", str_replace(" ", "", $this->home));
        if(isset($pos[3])) {
            $level = $this->plugin->getServer()->getLevelByName($pos[3]);
            if($level instanceof Level) {
                return new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), $pos[3]);
            }
            else {
                $this->plugin->getLogger()->critical("Couldn't parse {$this->name} kingdom home position due the level {$pos[3]} isn't valid! (Maybe it's not loaded?)");
                return null;
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return online players that are part of this kingdom.
     *
     * @return array
     */
    public function getPlayersByKingdom() {
        $players = [];
        /** @var KingdomPlayer $player */
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if($player->getKingdom() == $this) {
                $players[] = $player;
            }
        }
        return $players;
    }

    /**
     * Set kingdom name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Set kingdom points
     *
     * @param int $points
     */
    public function setPoints($points) {
        $this->points = $points;
    }

    /**
     * Set kingdom motto
     *
     * @param string $motto
     */
    public function setMotto($motto) {
        $this->motto = $motto;
    }

    /**
     * Set kingdom lost wars
     *
     * @param int $lostWars
     */
    public function setLostWars($lostWars) {
        $this->lostWars = $lostWars;
    }

    /**
     * Set kingdom won wars
     *
     * @param int $wonWars
     */
    public function setWonWars($wonWars) {
        $this->wonWars = $wonWars;
    }

    /**
     * Set kingdom home (it won't be parsed)
     *
     * @param string $home
     */
    public function setHome($home) {
        $this->home = $home;
    }

    /**
     * Set kingdom home (it will be parsed)
     *
     * @param Position $position
     */
    public function setHomePosition(Position $position) {
        $this->home = "{$position->getX()},{$position->getY()},{$position->getZ()},{$position->getLevel()->getName()}";
    }

}