<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 15:39
 */

namespace Kingdoms\models\kingdom;

use Kingdoms\Main;

class KingdomManager {

	/** @var Main */
	private $plugin;

	/** @var Kingdom[] */
	private $kingdoms = [];

	/**
	 * KingdomManager constructor.
	 *
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * Return Kingdoms
	 *
	 * @return Kingdom[]
	 */
	public function getKingdoms() {
		return $this->kingdoms;
	}

	/**
	 * Return if a Kingdom exists
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isKingdom($name) {
		return isset($this->kingdoms[strtoupper($name)]);
	}

	/**
	 * Return kingdom
	 *
	 * @param string $name
	 *
	 * @return Kingdom|null
	 */
	public function getKingdom($name) {
		if(isset($this->kingdoms[strtoupper($name)])) {
			return $this->kingdoms[strtoupper($name)];
		} else {
			$this->plugin->getLogger()->critical("Couldn't get kingdom {$name} due it's not registered on KingdomManager!");
			return null;
		}
	}

	/**
	 * Register Kingdom instance
	 *
	 * @param $name
	 * @param $points
	 * @param $motto
	 * @param $lostWars
	 * @param $wonWars
	 * @param $home
	 * @param $leader
	 */
	public function registerKingdom($name, $points, $motto, $lostWars, $wonWars, $home, $leader) {
		$this->kingdoms[$name] = new Kingdom($this->plugin, $name, $points, $motto, $lostWars, $wonWars, $home, $leader);
	}

	/**
	 * Delete a kingdom
	 *
	 * @param string $kingdom
	 */
	public function deleteKingdom($kingdom) {
		unset($this->kingdoms[$kingdom]);
		$this->plugin->getPluginDatabase()->getKingdomDatabase()->deleteKingdom($kingdom);
	}
}