<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 31/07/2016
 * Time: 11:47
 */

namespace Kingdoms\database\kingdom\request;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;
use pocketmine\Server;

class ListKingdomsRequest extends MySQLRequest {

	// Statuses
	const MYSQL_CONNECTION_ERROR = 0;
	const MYSQL_ERROR = 1;
	const MYSQL_SUCCESS = 2;

	/** @var string */
	private $name;

	/** @var int */
	private $page;

	/** @var int */
	private $minor;

	/** @var int */
	private $mayor;

	/**
	 * ListKingdomsRequest constructor.
	 *
	 * @param KingdomDatabase $database
	 * @param string $name
	 * @param $page
	 */
	public function __construct(KingdomDatabase $database, $name, $page) {
		parent::__construct($database->getCredentials());
		$this->name = $name;
		$this->page = ((int)$page <= 1) ? 1 : (int)$page;
		$this->minor = ($this->page <= 1) ? 1 : ($this->page - 1) * 5;
		$this->mayor = $this->minor + 4;
	}

	public function onRun() {
		$database = $this->getDatabase();
		if($database->connect_error) {
			$this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
		} else {
			$database->query("SET @rownum := 0");
			$result = $database->query("\nSELECT * FROM ( SELECT name, points, @rownum := @rownum + 1 AS rank FROM kingdoms ORDER BY points DESC ) as result WHERE rank >= {$this->minor} and rank <= {$this->mayor}");
			$result2 = $database->query("\nSELECT COUNT(*) as amount FROM kingdoms");
			if($result instanceof \mysqli_result and $result2 instanceof \mysqli_result) {
				$amount = round((int)$result2->fetch_assoc()["amount"] / 5);
				$result->free();
				$result2->free();
				$this->setResult([self::MYSQL_SUCCESS, $amount]);
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
					$server->getLogger()->info("Couldn't execute ListKingdomsRequest due connection error");
					throw new \RuntimeException($result[1]);
					break;
				case self::MYSQL_ERROR:
					$server->getLogger()->info("Couldn't execute ListKingdomsRequest due unknown error");
					break;
				case self::MYSQL_SUCCESS:
					$player = $plugin->getServer()->getPlayerExact($this->name);
					if($player instanceof KingdomsPlayer) {
						if($this->page <= $result[1]) {
							$database = $this->getDatabase();
							$player->sendPageAmount($this->page, $result[1]);
							$database->query("SET @rownum := 0");
							$result = $database->query("\nSELECT * FROM ( SELECT name, points, @rownum := @rownum + 1 AS rank FROM kingdoms ORDER BY points DESC ) as result WHERE rank >= {$this->minor} and rank <= {$this->mayor}");
							while($row = $result->fetch_assoc()) {
								$player->sendRankedKingdom($row["rank"], $row["name"], $row["points"]);
							}
							$result->free();
							$database->close();
							$server->getLogger()->info("ListKingdomsRequest was successfully executed!");
						} else {
							$player->sendKingdomMessage("KINGDOM_TOP_FAILED_REASON_PAGE");
						}
					}
					break;
			}
		}
	}

}