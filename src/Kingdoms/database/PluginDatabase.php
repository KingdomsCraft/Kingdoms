<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:09
 */

namespace Kingdoms\database;

use Kingdoms\database\kingdom\KingdomDatabase;
use Kingdoms\database\mysql\MySQLCredentials;

class PluginDatabase extends Database {

    /** @var MySQLCredentials */
    private $credentials;

    /** @var KingdomDatabase */
    private $kingdomDatabase;

    public function init() {
        $this->parseDatabaseCredentials();
        $this->setKingdomDatabase();
    }

    /**
     * Return MySQLCredentials instance
     *
     * @return MySQLCredentials
     */
    public function getCredentials() {
        return $this->credentials;
    }

    /**
     * Return KingdomDatabase instance
     *
     * @return KingdomDatabase
     */
    public function getKingdomDatabase() {
        return $this->kingdomDatabase;
    }

    /**
     * Set MySQLCredentials instance
     *
     * @param MySQLCredentials $credentials
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    /**
     * Register KingdomDatabase instance
     */
    public function setKingdomDatabase() {
        $this->kingdomDatabase = new KingdomDatabase($this->getPlugin(), $this->getCredentials());
    }

    /**
     * Parse database credentials
     */
    private function parseDatabaseCredentials() {
        $path = $this->getPlugin()->getDataFolder() . "database.json";
        if(is_file($path)) {
            $array = json_decode(file_get_contents($path), true);
            $this->credentials = new MySQLCredentials($array["host"], $array["username"], $array["password"], $array["database"], $array["port"]);
        }
        else {
            $this->getPlugin()->getLogger()->critical("Couldn't parse database credentials due there isn't a database.json file!");
        }
    }

}