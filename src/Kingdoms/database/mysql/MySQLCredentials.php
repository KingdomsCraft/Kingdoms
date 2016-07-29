<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 7:50
 */

namespace Kingdoms\database\mysql;

use Kingdoms\Base;
use Kingdoms\database\DatabaseManager;

class MySQLCredentials extends DatabaseManager {

    /** @var string */
    private $host;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var string */
    private $database;

    /** @var int */
    private $port;

    /**
     * MySQLCredentials constructor.
     *
     * @param Base $plugin
     * @param $credentials
     */
    public function __construct(Base $plugin, $credentials) {
        parent::__construct($plugin);
        $this->host = $credentials["host"];
        $this->username = $credentials["username"];
        $this->password = $credentials["password"];
        $this->database = $credentials["database"];
        $this->port = (int)$credentials["port"];
    }

}