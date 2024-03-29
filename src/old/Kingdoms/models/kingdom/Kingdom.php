<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 18:18
 */

namespace Kingdoms\models\kingdom;

use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\level\Level;
use pocketmine\level\Position;

class Kingdom {

	/** @var Main */
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

	/** @var string|null */
	private $leader;

	/**
	 * Kingdom constructor.
	 *
	 * @param Main $plugin
	 * @param string $name
	 * @param $points
	 * @param string $motto
	 * @param $lostWars
	 * @param $wonWars
	 * @param string $home
	 * @param string|null $leader
	 */
	public function __construct(Main $plugin, $name, $points, $motto, $lostWars, $wonWars, $home, $leader) {
		$this->plugin = $plugin;
		$this->name = $name;
		$this->points = (int)$points;
		$this->motto = $motto;
		$this->lostWars = (int)$lostWars;
		$this->wonWars = (int)$wonWars;
		$this->home = $home;
		$this->leader = $leader;
	}

	/**
	 * Return Kingdom name if the object is used as a string.
	 * Anyway, use it as a string is bad practice, if you're
	 * going to get the name, use getName method instead!
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getName();
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
	 * Set kingdom name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Return Kingdom data
	 *
	 * @return array
	 */
	public function getData() {
		return ["name" => $this->name, "points" => $this->points, "motto" => $this->motto, "lostWars" => $this->lostWars, "wonWars" => $this->wonWars, "home" => $this->home, "leader" => $this->leader];
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
	 * Set kingdom points
	 *
	 * @param int $points
	 */
	public function setPoints($points) {
		$this->points = $points;
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
	 * Set kingdom lost wars
	 *
	 * @param int $lostWars
	 */
	public function setLostWars($lostWars) {
		$this->lostWars = $lostWars;
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
	 * Set kingdom motto
	 *
	 * @param string $motto
	 */
	public function setMotto($motto) {
		$this->motto = $motto;
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
	 * Set kingdom won wars
	 *
	 * @param int $wonWars
	 */
	public function setWonWars($wonWars) {
		$this->wonWars = $wonWars;
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
	 * Set kingdom home (it won't be parsed)
	 *
	 * @param string $home
	 */
	public function setHome($home) {
		$this->home = $home;
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
				return new Position(intval($pos[0]), intval($pos[1]), intval($pos[2]), $level);
			} else {
				$this->plugin->getLogger()->critical("Couldn't parse {$this->name} kingdom home position due the level {$pos[3]} isn't valid! (Maybe it's not loaded?)");
				return null;
			}
		} else {
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
		/** @var KingdomsPlayer $player */
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if($player->getKingdom() == $this) {
				$players[] = $player;
			}
		}
		return $players;
	}

	/**
	 * Return kingdom leader
	 *
	 * @return string
	 */
	public function getLeader() {
		return $this->leader;
	}

	/**
	 * Set kingdom leader
	 *
	 * @param null|string $name
	 */
	public function setLeader($name) {
		$this->leader = strtolower($name);
	}

	/**
	 * Add kingdom points
	 *
	 * @param int $points
	 */
	public function addPoints($points) {
		$this->points += $points;
	}

	/**
	 * Remove kingdom points
	 *
	 * @param $points
	 */
	public function removePoints($points) {
		$this->points -= $points;
		if($this->points < 0) {
			$this->points = 0;
		}
	}

	/**
	 * Set kingdom home (it will be parsed)
	 *
	 * @param Position $position
	 */
	public function setHomePosition(Position $position) {
		$this->home = "{$position->getX()},{$position->getY()},{$position->getZ()},{$position->getLevel()->getName()}";
	}

	/**
	 * Update the kingdom
	 */
	public function update() {
		$this->plugin->getPluginDatabase()->getKingdomDatabase()->updateKingdom($this);
	}

}