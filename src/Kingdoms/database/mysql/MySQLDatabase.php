<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:31
 */

namespace Kingdoms\database\mysql;

use Kingdoms\database\Database;
use Kingdoms\Main;

abstract class MySQLDatabase extends Database {

    /** @var MySQLCredentials */
    private $credentials;

    /**
     * MySQLDatabase constructor.
     *
     * @param Main $plugin
     * @param MySQLCredentials $credentials
     */
    public function __construct(Main $plugin, MySQLCredentials $credentials) {
        $this->credentials = $credentials;
        parent::__construct($plugin);
    }

    /**
     * Return MySQLCredentials instance
     *
     * @return MySQLCredentials
     */
    public function getCredentials() {
        return $this->credentials;
    }

}