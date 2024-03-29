<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 18:17
 */

namespace Kingdoms\models\guild;

use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\level\Level;
use pocketmine\level\Position;

class Guild {

	const CLASS_ASSASSIN = 0;
	const CLASS_FARMER = 1;
	const CLASS_WARRIOR = 2;
	/** @var Main */
	private $plugin;
	/** @var string */
	private $name;
	/** @var string|null */
	private $leader;

	// Classes
	/** @var string */
	private $motto;
	/** @var int */
	private $points;
	/** @var int */
	private $class;
	/** @var int */
	private $vault;

	/** @var string */
	private $home;

	/** @var string */
	private $kingdom;

	/**
	 * Guild constructor.
	 *
	 * @param Main $plugin
	 * @param string $name
	 * @param string|null $leader
	 * @param string $motto
	 * @param $points
	 * @param $class
	 * @param $vault
	 * @param string $home
	 * @param string $kingdom
	 */
	public function __construct(Main $plugin, $leader, $name, $motto, $points, $class, $vault, $home, $kingdom) {
		$this->plugin = $plugin;
		$this->name = $name;
		$this->leader = $leader;
		$this->motto = $motto;
		$this->points = (int)$points;
		$this->class = (int)$class;
		$this->vault = (int)$vault;
		$this->home = $home;
		$this->kingdom = $kingdom;
	}

	/**
	 * Return guild data
	 *
	 * @return array
	 */
	public function getData() {
		return ["name" => $this->name, "leader" => $this->leader, "motto" => $this->motto, "points" => $this->points, "class" => $this->class, "vault" => $this->vault, "home" => $this->home, "kingdom" => $this->kingdom];
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
	 * Set guild name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
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
	 * Set guild motto
	 *
	 * @param string $motto
	 */
	public function setMotto($motto) {
		$this->motto = $motto;
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
	 * Set guild class
	 *
	 * @param int $class
	 */
	public function setClass($class) {
		$this->class = $class;
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
	 * Set guild home (not parsed)
	 *
	 * @param string $home
	 */
	public function setHome($home) {
		$this->home = $home;
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
			} else {
				$this->plugin->getLogger()->critical("Couldn't parse {$this->name} guild home position due the level {$pos[3]} isn't valid! (Maybe it's not loaded?)");
				return null;
			}
		} else {
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
	 * Set guild points
	 *
	 * @param int $amount
	 */
	public function setPoints($amount) {
		$this->points = $amount;
	}

	/**
	 * Return guild leader
	 *
	 * @return null|string
	 */
	public function getLeader() {
		return $this->leader;
	}

	/**
	 * Set guild leader
	 *
	 * @param string|null $leader
	 */
	public function setLeader($leader) {
		if($leader != $this->leader) {
			$player = $this->plugin->getServer()->getPlayerExact($this->leader);
			if($player instanceof KingdomsPlayer and $player->isOnline()) {
				$player->setLeader(false);
			}
		}
		$this->leader = $leader;
	}

	/**
	 * Return guild kingdom
	 *
	 * @return string
	 */
	public function getKingdom() {
		return $this->kingdom;
	}

	/**
	 * Set guild kingdom
	 *
	 * @param string $kingdom
	 */
	public function setKingdom($kingdom) {
		$this->kingdom = $kingdom;
	}

	/**
	 * Return online players by guild
	 *
	 * @return KingdomsPlayer[]
	 */
	public function getOnlinePlayers() {
		$players = [];
		/** @var KingdomsPlayer $player */
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
			if($player->getGuild() == $this) {
				$players[] = $player;
			}
		}
		return $player;
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
	 * Add rubies to the vault
	 *
	 * @param int $amount
	 */
	public function addRubies($amount) {
		$this->vault += $amount;
	}

	/**
	 * Remove guild rubies from the vault
	 *
	 * @param int $amount
	 */
	public function removeRubies($amount) {
		$this->vault -= $amount;
		if($this->vault < 0) {
			$this->vault = 0;
		}
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
	 * Update the guild
	 */
	public function update() {
		$this->plugin->getPluginDatabase()->getGuildDatabase()->updateGuild($this);
	}
}