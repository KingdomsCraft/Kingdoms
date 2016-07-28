<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 14:38
 */

namespace Kingdoms\database;

use Kingdoms\Base;
use Kingdoms\database\request\guild\UpdateGuildRequest;
use Kingdoms\database\request\InitializeDatabaseRequest;
use Kingdoms\database\request\kingdom\CreateKingdomRequest;
use Kingdoms\database\request\kingdom\InitializeKingdomRequest;
use Kingdoms\database\request\kingdom\UpdateKingdomRequest;
use Kingdoms\database\request\player\InitializePlayerRequest;
use Kingdoms\database\request\player\TopGuildsPlayerRequest;
use Kingdoms\database\request\player\UpdatePlayerRequest;
use Kingdoms\KingdomPlayer;

class DatabaseManager {

    /** @var Base */
    private $plugin;

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
     * DatabaseManager constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    public function init() {
        $this->loadCredentials();
        $this->initializeDatabase();
    }

    /**
     * Load database credentials
     */
    public function loadCredentials() {
        $array = $this->plugin->getConfig()->get("mysql");
        $this->address = $array["address"];
        $this->username = $array["username"];
        $this->password = $array["password"];
        $this->database = $array["database"];
        $this->port = (int)$array["port"];
    }

    /**
     * Return database instance
     *
     * @return \mysqli|null
     */
    public function getDatabase() {
        $mysql = new \mysqli($this->address, $this->username, $this->password, $this->database, $this->port);
        if($mysql->connect_errno) {
            $this->plugin->getLogger()->critical("Couldn't connect to database!");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
            return null;
        }
        else {
            return $mysql;
        }
    }

    /**
     * Return Kingdoms Base
     *
     * @return Base
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Return database address
     *
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     *  Return database username
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
     * Return database database name
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
     * Return database credentials
     *
     * @return array
     */
    public function getDatabaseCredentials() {
        return [
            "address" => $this->address,
            "username" => $this->username,
            "password" => $this->password,
            "database" => $this->database,
            "port" => $this->port
        ];
    }

    /**
     * Set database address
     *
     * @param string $address
     */
    public function setAddress($address) {
        $this->address = $address;
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
     * Set database database name
     *
     * @param string $database
     */
    public function setDatabase($database) {
        $this->database = $database;
    }

    /**
     * Set database port
     *
     * @param int $port
     */
    public function setPort($port) {
        $this->port = $port;
    }

    /**
     * Set database password
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    // Tasks

    public function initializeUpdater() {
        $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask(new UpdateTask($this), 20 * 60);
    }

    public function initializeDatabase() {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new InitializeDatabaseRequest($this->getDatabaseCredentials()));
    }

    public function initializeKingdoms() {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new InitializeKingdomRequest($this->getDatabaseCredentials()));
    }

    public function initializeGuild($guild) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new UpdateGuildRequest($this->getDatabaseCredentials(), $guild));
    }

    public function initializePlayer($player) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new InitializePlayerRequest($this->getDatabaseCredentials(), $player));
    }

    public function updateKingdom($kingdom) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new UpdateKingdomRequest($this->getDatabaseCredentials(), $kingdom));
    }

    public function updateGuild($guild) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new UpdateGuildRequest($this->getDatabaseCredentials(), $guild));
    }

    public function updatePlayer($player) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new UpdatePlayerRequest($this->getDatabaseCredentials(), $player));
    }

    /**
     * @param string $kingdom
     * @param string $motto
     */
    public function createKingdom($kingdom, $motto = "This my amazing kingdom!") {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new CreateKingdomRequest($this->getDatabaseCredentials(), $kingdom, $motto));
    }

    /**
     * @param KingdomPlayer $player
     * @param string $kingdom
     * @param int $page
     */
    public function sendTopGuilds(KingdomPlayer $player, $kingdom, $page) {
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask(new TopGuildsPlayerRequest($this->getDatabaseCredentials(), $player->getName(), $kingdom, $page));
    }

}