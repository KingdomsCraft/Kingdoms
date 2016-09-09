<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 14:52
 */

namespace Kingdoms\database\guild\request;

use Kingdoms\database\guild\GuildDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\Main;
use pocketmine\Server;

class RegisterGuildRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

	/** @var string */
	private $name;

	/** @var string */
	private $motto;

	/** @var string */
	private $kingdom;

	/** @var string */
	private $leader;

	/**
	 * RegisterGuildRequest constructor.
	 *
	 * @param GuildDatabase $database
	 * @param string $name
	 * @param string $motto
	 * @param string $kingdom
	 * @param string $leader
	 */
	public function __construct(GuildDatabase $database, $name, $motto, $kingdom, $leader) {
		parent::__construct($database->getCredentials());
		$this->name = strtoupper($name);
		$this->motto = $motto;
		$this->kingdom = strtoupper($kingdom);
		$this->leader = strtolower($leader);
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$database->query("\nINSERT INTO guilds (name, leader, motto, kingdom) VALUES ('{$database->escape_string($this->name)}', '{$database->escape_string($this->leader)}', '{$database->escape_string($this->motto)}', '{$database->escape_string($this->kingdom)}')");
			$database->query("\nUPDATE kingdoms_players SET guild='{$database->escape_string($this->name)}', leader='1' WHERE name='{$this->leader}'");
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
					$server->getLogger()->info("Couldn't execute RegisterGuildRequest due connection error!");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute RegisterGuildRequest due unknown error!");
					break;
				case self::MYSQL_SUCCESS:
					$server->getLogger()->info("RegisterGuildRequest was successfully executed!");
					break;
			}
		}
	}

}