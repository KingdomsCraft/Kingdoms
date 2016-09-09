<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 9:50
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;
use Kingdoms\Main;
use pocketmine\Server;

class InitDatabaseRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

	/**
	 * InitDatabaseRequest constructor.
	 *
	 * @param PlayerDatabase $database
	 */
	public function __construct(PlayerDatabase $database) {
		parent::__construct($database->getCredentials());
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$database->query("\nCREATE TABLE IF NOT EXISTS kingdoms_players (
            name VARCHAR(32) PRIMARY KEY,
            kingdom VARCHAR(32) DEFAULT '',
            kingdomRank TINYINT DEFAULT 0,
            guild VARCHAR(32) DEFAULT '',
            admin INT DEFAULT 0,
            leader INT DEFAULT 0)");
			if(isset($database->error) and $database->error) {
				$this->setResult([self::MYSQL_ERROR, $database->error]);
			} else {
				$this->setResult([self::MYSQL_SUCCESS]);
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
					$server->getLogger()->info("Couldn't execute InitDatabaseRequest (players) due connection error!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute InitDatabaseRequest (players) due error {$result[1]}!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_SUCCESS:
					$server->getLogger()->info("InitDatabaseRequest (players) was successfully executed!");
					break;
			}
		}
	}

}