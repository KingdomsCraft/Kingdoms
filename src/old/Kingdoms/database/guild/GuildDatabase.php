<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 8:55
 */

namespace Kingdoms\database\guild;

use Kingdoms\database\guild\request\DeleteGuildRequest;
use Kingdoms\database\guild\request\InitDatabaseRequest;
use Kingdoms\database\guild\request\InitGuildRequest;
use Kingdoms\database\guild\request\RegisterGuildRequest;
use Kingdoms\database\guild\request\UpdateGuildRequest;
use Kingdoms\database\mysql\MySQLDatabase;
use Kingdoms\database\mysql\MySQLUpdate;
use Kingdoms\models\guild\Guild;

class GuildDatabase extends MySQLDatabase {

	/**
	 * Initialize GuildDatabase
	 */
	public function init() {
		$this->initDatabase();
		$this->getPlugin()->getServer()->getScheduler()->scheduleRepeatingTask(new MySQLUpdate($this->getPlugin()), 20 * 120);
	}

	/**
	 * Initialize guild database
	 */
	public function initDatabase() {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
	}

	/**
	 * Initialize a guild
	 *
	 * @param string $name
	 */
	public function initGuild($name) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitGuildRequest($this, $name));
	}

	/**
	 * Update a guild
	 *
	 * @param Guild $guild
	 */
	public function updateGuild(Guild $guild) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateGuildRequest($this, $guild->getData()));
	}

	/**
	 * Register a guild
	 *
	 * @param string $name
	 * @param string $motto
	 * @param string $kingdom
	 * @param string $leader
	 */
	public function registerGuild($name, $motto, $kingdom, $leader) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new RegisterGuildRequest($this, $name, $motto, $kingdom, $leader));
	}

	/**
	 * Delete a guild
	 *
	 * @param string $name
	 */
	public function deleteGuild($name) {
		$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new DeleteGuildRequest($this, $name));
	}

	public function isFree($guild) {
		$guild = strtoupper($guild);
		$database = $this->getCredentials()->getDatabase();
		$result = $database->query("SELECT COUNT(*) as amount FROM guilds WHERE guild='{$database->escape_string($guild)}'");
		if($result instanceof \mysqli_result) {
			$row = $result->fetch_assoc();
			$result->free();
			if(is_array($row)) {
				return (int)$row["amount"] <= 3;
			} else {
				return true;
			}
		}
		$database->close();
		return false;
	}

}