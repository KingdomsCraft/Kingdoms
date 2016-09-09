<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:11
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class RegisterKingdomRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

	/** @var string */
	private $name;

	/** @var string */
	private $motto;

	/**
	 * RegisterKingdomRequest constructor.
	 *
	 * @param KingdomDatabase $database
	 * @param string $name
	 * @param string $motto
	 */
	public function __construct(KingdomDatabase $database, $name, $motto) {
		parent::__construct($database->getCredentials());
		$this->name = $name;
		$this->motto = $motto;
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$database->query("\nINSERT INTO kingdoms (name, motto, points, wonWars, lostWars, home) VALUES (
            '{$database->escape_string($this->name)}',
            '{$database->escape_string($this->motto)}',
            0,0,0,'')");
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
					$server->getLogger()->info("Couldn't execute RegisterKingdomRequest due connection error!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute RegisterKingdomRequest due unknown error!");
					break;
				case self::MYSQL_SUCCESS:
					$server->getLogger()->info("RegisterKingdomRequest was successfully created with {$this->name}");
					$plugin->getKingdomManager()->registerKingdom($this->name, 0, $this->motto, 0, 0, '', '');
					break;
				default:
					$server->getLogger()->info("Couldn't execute RegisterKingdomRequest due unknown error");
					break;
			}
		}
	}

}