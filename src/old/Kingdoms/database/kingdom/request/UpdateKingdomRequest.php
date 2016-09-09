<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 21:49
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class UpdateKingdomRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

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
	 * UpdateKingdomRequest constructor.
	 *
	 * @param KingdomDatabase $database
	 * @param array $data
	 */
	public function __construct(KingdomDatabase $database, $data) {
		parent::__construct($database->getCredentials());
		$this->name = $data["name"];
		$this->points = $data["points"];
		$this->motto = $data["motto"];
		$this->lostWars = $data["lostWars"];
		$this->wonWars = $data["wonWars"];
		$this->home = $data["home"];
		$this->leader = $data["leader"];
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$leader = (empty($this->leader)) ? '' : strtolower($this->leader);
			$database->query("\nUPDATE kingdoms SET name='{$database->escape_string($this->name)}', points='{$this->points}', motto='{$database->escape_string($this->motto)}', lostWars='{$this->lostWars}', wonWars='{$this->wonWars}', home='{$database->escape_string($this->home)}', leader='{$leader}' WHERE name='{$database->escape_string($this->name)}'");
			if($database->affected_rows > 0) {
				$this->setResult([self::MYSQL_SUCCESS]);
			} else {
				$this->setResult([self::MYSQL_ERROR]);
			}
		}
		$database->close();
	}

	public function onCompletion(Server $server) {
		$plugin = $this->getPlugin($server);
		if($plugin instanceof Main and $plugin->isEnabled()) {
			$result = $this->getResult();
			switch($result[0]) {
				case self::MYSQL_CONNECTION_ERROR:
					$server->getLogger()->info("Couldn't execute UpdateKingdomRequest due connection error!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute UpdateKingdomRequest due unknown error! (or nothing to update!)");
					break;
				case self::MYSQL_SUCCESS:
					$server->getLogger()->info("Kingdom {$this->name} was successfully updated!");
					break;
			}
		}
	}

}