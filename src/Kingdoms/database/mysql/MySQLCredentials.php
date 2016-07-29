<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:12
 */

namespace Kingdoms\database\mysql;

class MySQLCredentials {

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
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param int $port
     */
    public function __construct($host, $username, $password, $database, $port) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = (int) $port;
    }

    /**
     * Return \mysqli instance
     *
     * @return \mysqli
     */
    public function getDatabase() {
        return new \mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
    }

    /**
     * Return database host (aka address)
     *
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * Return database username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Return database password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Return database name
     *
     * @return string
     */
    public function getDatabaseName() {
        return $this->database;
    }

    /**
     * Return database port
     *
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * Set database host
     *
     * @param string $host
     */
    public function setHost($host) {
        $this->host = $host;
    }

    /**
     * Set database username
     *
     * @param string $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * Set database password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Set database name
     *
     * @param string $database
     */
    public function setDatabaseName($database) {
        $this->database = $database;
    }

    /**
     * Set database port
     *
     * @param int $port
     */
    public function setPort($port) {
        $this->port = (int) $port;
    }



}