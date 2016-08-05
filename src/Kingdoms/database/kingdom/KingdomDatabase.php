<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 22:03
 */

namespace Kingdoms\database\kingdom;

use Kingdoms\database\kingdom\request\AddCoinsByPlayerNameKingdomRequest;
use Kingdoms\database\kingdom\request\DeleteKingdomRequest;
use Kingdoms\database\kingdom\request\InitDatabaseRequest;
use Kingdoms\database\kingdom\request\InitKingdomsRequest;
use Kingdoms\database\kingdom\request\ListKingdomsRequest;
use Kingdoms\database\kingdom\request\RegisterKingdomRequest;
use Kingdoms\database\kingdom\request\ShowKingdomInfoRequest;
use Kingdoms\database\kingdom\request\UpdateKingdomRequest;
use Kingdoms\database\mysql\MySQLDatabase;
use Kingdoms\models\kingdom\Kingdom;

class KingdomDatabase extends MySQLDatabase {

    /**
     * Initialize the class
     */
    public function init() {
        $this->initDatabase();
        $this->initKingdoms();
    }

    /**
     * Initialize the database
     */
    public function initDatabase() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitDatabaseRequest($this));
    }

    /**
     * Initialize all kingdoms
     */
    public function initKingdoms() {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new InitKingdomsRequest($this));
    }

    /**
     * Update a kingdom
     *
     * @param Kingdom $kingdom
     */
    public function updateKingdom(Kingdom $kingdom) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateKingdomRequest($this, $kingdom->getData()));
    }

    /**
     * Register a kingdom
     *
     * @param string $kingdom
     * @param string $motto
     */
    public function registerKingdom($kingdom, $motto) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new RegisterKingdomRequest($this, $kingdom, $motto));
    }

    /**
     * List kingdoms position
     *
     * @param string $name
     * @param $page
     */
    public function listKingdomList($name, $page) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new ListKingdomsRequest($this, $name, $page));
    }

    /**
     * Show kingdom info
     *
     * @param string $name
     * @param string $player
     */
    public function showKingdomInfo($name, $player) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new ShowKingdomInfoRequest($this, $name, $player));
    }

    /**
     * Delete a kingdom
     *
     * @param string $name
     */
    public function deleteKingdom($name) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new DeleteKingdomRequest($this, $name));
    }

    /**
     * Add coins by a player name, remove if it's a negative number!
     *
     * @param $name
     * @param $amount
     */
    public function addCoinsByPlayerName($name, $amount) {
        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new AddCoinsByPlayerNameKingdomRequest($this, $name, $amount));
    }

}