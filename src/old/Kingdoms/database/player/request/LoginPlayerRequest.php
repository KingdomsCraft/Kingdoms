<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 9:48
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use Kingdoms\models\guild\Guild;
use pocketmine\Server;

class LoginPlayerRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_NEED_REGISTRATION = 2;
	const MYSQL_SUCCESS = 3;

	/** @var string */
	private $name;

	/**
	 * LoginPlayerRequest constructor.
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
			$result = $database->query("\nSELECT * FROM kingdoms_players WHERE name='{$database->escape_string($this->name)}'");
			if($result instanceof \mysqli_result) {
				$row = $result->fetch_assoc();
				$result->free();
				if(is_array($row)) {
					$this->setResult([self::MYSQL_SUCCESS, $row]);
				} else {
					$this->setResult([self::MYSQL_NEED_REGISTRATION]);
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
			$player = $plugin->getServer()->getPlayerExact($this->name);
			if($player instanceof KingdomsPlayer) {
				$result = $this->getResult();
				switch($result[0]) {
					case self::MYSQL_CONNECTION_ERROR:
						$server->getLogger()->info("Couldn't execute LoginPlayerRequest due connection error!");
						throw new \RuntimeException($result[1]);
						break;
					case self::MYSQL_ERROR:
						$server->getLogger()->info("Couldn't execute LoginPlayerRequest due unknown error!");
						break;
					case self::MYSQL_NEED_REGISTRATION:
						$plugin->getPluginDatabase()->getPlayerDatabase()->registerPlayer($this->name);
						$server->getLogger()->info("LoginPlayerRequest needs registration with {$this->name}");
						break;
					case self::MYSQL_SUCCESS:
						$row = $result[1];
						$kingdomManager = $plugin->getKingdomManager();
						if(empty($row["kingdom"])) {
							$player->setKingdom(null);
						} elseif($kingdomManager->isKingdom($row["kingdom"])) {
							$player->setKingdom($kingdomManager->getKingdom($row["kingdom"]));
						}
						$player->setKingdomRank((int)$row["kingdomRank"]);
						$guildManager = $plugin->getGuildManager();
						if(empty($row["guild"])) {
							$player->setGuild(null);
						} elseif($guildManager->isGuild($row["guild"])) {
							$player->setGuild($guildManager->getGuild($row["guild"]));
						} else {
							$plugin->getPluginDatabase()->getGuildDatabase()->initGuild($row["guild"]);
							if($guildManager->isGuild($row["guild"])) {
								/** @var Guild $guild */
								$guild = $guildManager->getGuild($row["guild"]);
								$player->setGuild($guild);
								if(strtolower($player->getName()) == $guild->getLeader()) {
									$player->setLeader();
								}
							}
						}
						$player->setAdmin($row["admin"] ? true : false);
						$player->setLeader($row["leader"] ? true : false);
						$server->getLogger()->info("LoginPlayerRequest successfully executed with {$this->name}");
						break;
				}
			}
		}
	}

}