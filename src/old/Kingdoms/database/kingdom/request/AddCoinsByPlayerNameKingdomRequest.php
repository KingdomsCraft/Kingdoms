<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 7:29
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class AddCoinsByPlayerNameKingdomRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_EMPTY = 2;
	const MYSQL_SUCCESS = 3;

	/** @var string */
	private $name;

	/** @var int */
	private $amount;

	/**
	 * AddCoinsByPlayerNameKingdomRequest constructor.
	 *
	 * @param KingdomDatabase $database
	 * @param string $name
	 * @param $amount
	 */
	public function __construct(KingdomDatabase $database, $name, $amount) {
		parent::__construct($database->getCredentials());
		$this->name = strtolower($name);
		$this->amount = (int)$amount;
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$result = $database->query("\nSELECT kingdom FROM kingdoms_players WHERE name='{$database->escape_string($this->name)}'");
			if($result instanceof \mysqli_result) {
				$kingdom = $result->fetch_assoc()["kingdom"];
				$kingdom = (empty($kingdom)) ? null : $kingdom;
				$result->free();
				if(is_null($kingdom)) {
					$this->setResult([self::MYSQL_EMPTY]);
				} else {
					$this->setResult([self::MYSQL_SUCCESS, $kingdom]);
				}
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
					$server->getLogger()->info("Couldn't execute AddCoinsByPlayerNameKingdomRequest due connection error!");
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute AddCoinsByPlayerNameKingdomRequest due unknown error!");
					break;
				case self::MYSQL_EMPTY:
					$server->getLogger()->info("The player {$this->name} isn't in a kingdom!");
					break;
				case self::MYSQL_SUCCESS:
					$database = $this->getDatabase();
					$kingdom = $result[1];
					$database->query("\nUPDATE kingdoms SET points=points+{$this->amount} WHERE name='{$database->escape_string($kingdom)}'");
					if($database->affected_rows > 0) {
						$server->getLogger()->info("AddCoinsByPlayerNameKingdomRequest was successfully executed");
					} else {
						$server->getLogger()->info("AddCoinsByPlayerNameKingdomRequest was executed.");
					}
					$database->close();
					break;
			}
		}
	}

}