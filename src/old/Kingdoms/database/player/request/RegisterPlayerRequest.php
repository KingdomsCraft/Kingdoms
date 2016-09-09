<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 11:08
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class RegisterPlayerRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

	/** @var string */
	private $name;

	/**
	 * RegisterPlayerRequest constructor.
	 *
	 * @param PlayerDatabase $database
	 * @param $name
	 */
	public function __construct(PlayerDatabase $database, $name) {
		parent::__construct($database->getCredentials());
		$this->name = strtolower($name);
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$database->query("\nINSERT INTO kingdoms_players (name) VALUES ('{$database->escape_string($this->name)}')");
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
			$player = $plugin->getServer()->getPlayerExact($this->name);
			if($player instanceof KingdomsPlayer) {
				$result = $this->getResult();
				switch($result[0]) {
					case self::MYSQL_CONNECTION_ERROR:
						$server->getLogger()->info("Couldn't execute RegisterPlayerRequest due connection error!");
						throw new \RuntimeException($result[1]);
						break;
					case self::MYSQL_ERROR:
						$server->getLogger()->info("Couldn't execute RegisterPlayerRequest due unknown error!");
						break;
					case self::MYSQL_SUCCESS:
						$server->getLogger()->info("[RegisterPlayerRequest] {$this->name} was registered successfully!");
						break;
				}
			}
		}
	}

}