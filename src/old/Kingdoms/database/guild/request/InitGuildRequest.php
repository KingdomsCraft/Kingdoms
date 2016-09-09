<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 9:13
 */

namespace Kingdoms\database\guild\request;

use Kingdoms\database\guild\GuildDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class InitGuildRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_FAILURE = 2;
	const MYSQL_SUCCESS = 3;

	/** @var string */
	private $name;

	/**
	 * InitGuildRequest constructor.
	 *
	 * @param GuildDatabase $database
	 * @param string $name
	 */
	public function __construct(GuildDatabase $database, $name) {
		parent::__construct($database->getCredentials());
		$this->name = strtoupper($name);
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$result = $database->query("\nSELECT * FROM guilds WHERE name='{$database->escape_string($this->name)}'");
			if($result instanceof \mysqli_result) {
				$row = $result->fetch_assoc();
				$result->free();
				if(is_array($row)) {
					$this->setResult([self::MYSQL_SUCCESS, $row]);
				} else {
					$this->setResult([self::MYSQL_FAILURE]);
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
					$server->getLogger()->info("Couldn't execute InitGuildRequest due connection error!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute InitGuildRequest due unknown error!");
					break;
				case self::MYSQL_FAILURE:
					$server->getLogger()->info("Couldn't execute InitGuildRequest due {$this->name} isn't a valid guild!");
					break;
				case self::MYSQL_SUCCESS:
					$row = $result[1];
					$leader = (empty($row["leader"])) ? null : $row["leader"];
					$plugin->getGuildManager()->registerGuild($row["name"], $leader, $row["motto"], $row["points"], $row["class"], $row["vault"], $row["home"], $row["kingdom"]);
					$server->getLogger()->info("{$this->name} guild was successfully registered!");
					break;
			}
		}
	}

}