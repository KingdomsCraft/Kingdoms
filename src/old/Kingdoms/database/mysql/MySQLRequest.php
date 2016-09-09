<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:25
 */

namespace Kingdoms\database\mysql;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

abstract class MySQLRequest extends AsyncTask {

	/** @var MySQLCredentials */
	private $credentials;

	/**
	 * MySQLRequest constructor.
	 *
	 * @param MySQLCredentials $credentials
	 */
	public function __construct(MySQLCredentials $credentials) {
		$this->credentials = $credentials;
	}

	/**
	 * Return database instance
	 *
	 * @return \mysqli
	 */
	public function getDatabase() {
		return $this->credentials->getDatabase();
	}

	/**
	 * Return Main instance
	 *
	 * @param Server $server
	 *
	 * @return null|\pocketmine\plugin\Plugin
	 */
	public function getPlugin(Server $server) {
		return $server->getPluginManager()->getPlugin("Kingdoms");
	}

}