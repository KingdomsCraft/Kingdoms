<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 14:39
 */

namespace Kingdoms\database\request;

use Kingdoms\Base;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

abstract class Request extends AsyncTask {

    /** @var string */
    private $address;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $database;

    /** @var int */
    private $port;

    /**
     * Request constructor.
     * @param array $credentials
     */
    public function __construct($credentials) {
        $this->address = $credentials["address"];
        $this->username = $credentials["username"];
        $this->password = $credentials["password"];
        $this->database = $credentials["database"];
        $this->port = $credentials["port"];
    }

    /**
     * Return database instance
     *
     * @return \mysqli
     */
    public function getDatabase() {
        return new \mysqli($this->address, $this->username, $this->password, $this->database, $this->port);
    }

    /**
     * Return Base instance, null if not able to find
     *
     * @param Server $server
     * @return null|\pocketmine\plugin\Plugin
     */
    public function getPlugin(Server $server) {
        $plugin = $server->getPluginManager()->getPlugin("Kingdoms");
        if($plugin instanceof Base and $plugin->isEnabled()) {
            return $plugin;
        }
        else {
            return null;
        }
    }

}