<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 18:17
 */

namespace Kingdoms\models\guild;

use Kingdoms\Main;
use pocketmine\level\Level;
use pocketmine\level\Position;

class Guild {

    /** @var Main */
    private $plugin;

    /** @var string */
    private $name;

    /** @var string */
    private $leader;

    /** @var string */
    private $motto;

    /** @var int */
    private $points;

    /** @var int */
    private $class;

    // Classes
    const CLASS_ASSASSIN = 0;
    const CLASS_FARMER = 1;
    const CLASS_WARRIOR = 2;

    /** @var int */
    private $vault;

    /** @var string */
    private $home;

    /**
     * Guild constructor.
     * @param Main $plugin
     * @param string $name
     * @param string $leader
     * @param string $motto
     * @param int $points
     * @param int $class
     * @param int $vault
     * @param string $home
     */
    public function __construct(Main $plugin, $leader, $name, $motto, $points, $class, $vault, $home) {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->leader = $leader;
        $this->motto = $motto;
        $this->points = $points;
        $this->class = $class;
        $this->vault = $vault;
        $this->home = $home;
    }

    /**
     * Return Base instance
     *
     * @return Main
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Return guild name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Return guild motto/description
     *
     * @return string
     */
    public function getMotto() {
        return $this->motto;
    }

    /**
     * Return guild class
     *
     * @return int
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * Return guild rubies in the vault
     *
     * @return int
     */
    public function getRubies() {
        return $this->vault;
    }

    /**
     * Return guild home (not parsed)
     *
     * @return string
     */
    public function getHome() {
        return $this->home;
    }

    /**
     * Return guild home (parsed)
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
                $this->plugin->getLogger()->critical("Couldn't parse {$this->name} guild home position due the level {$pos[3]} isn't valid! (Maybe it's not loaded?)");
                return null;
            }
        }
        else {
            return null;
        }
    }

    /**
     * Return guild points
     *
     * @return int
     */
    public function getPoints() {
        return $this->points;
    }

    /**
     * Set guild name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Set guild class
     *
     * @param int $class
     */
    public function setClass($class) {
        $this->class = $class;
    }

    /**
     * Set guild motto
     *
     * @param string $motto
     */
    public function setMotto($motto) {
        $this->motto = $motto;
    }

    /**
     * Set guild rubies in the vault
     *
     * @param int $amount
     */
    public function setRubies($amount) {
        $this->vault = $amount;
    }

    /**
     * Set guild home (not parsed)
     *
     * @param string $home
     */
    public function setHome($home) {
        $this->home = $home;
    }

    /**
     * Set guild home (it will be parsed)
     *
     * @param Position $position
     */
    public function setHomePosition(Position $position) {
        $this->home = "{$position->getX()},{$position->getY()},{$position->getZ()},{$position->getLevel()->getName()}";
    }

    /**
     * Set guild points
     *
     * @param int $amount
     */
    public function setPoints($amount) {
        $this->points = $amount;
    }
}