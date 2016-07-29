<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:09
 */

namespace Kingdoms\database;

use Kingdoms\database\mysql\MySQLCredentials;

class KingdomsDatabase extends Database {

    /** @var MySQLCredentials */
    private $credentials;

    public function init() {
        $this->parseDatabaseCredentials();
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
     * Set MySQLCredentials instance
     *
     * @param MySQLCredentials $credentials
     */
    public function setCredentials($credentials) {
        $this->credentials = $credentials;
    }

    /**
     * Parse database credentials
     */
    private function parseDatabaseCredentials() {
        $path = $this->getPlugin()->getDataFolder() . "database.json";
        if(is_file($path)) {
            $array = json_decode(file_get_contents($path), true);
            $this->setCredentials(new MySQLCredentials($array["host"], $array["username"], $array["password"], $array["database"], $array["port"]));
        }
        else {
            $this->getPlugin()->getLogger()->critical("Couldn't parse database credentials due there isn't a database.json file!");
        }
    }

}