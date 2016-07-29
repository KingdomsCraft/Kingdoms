<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:09
 */

namespace Kingdoms\database;

use Kingdoms\Main;

abstract class Database {

    /** @var Main */
    private $plugin;

    /**
     * Database constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public function getPlugin() {
        return $this->plugin;
    }

    /**
     * Initialize the database
     */
    protected abstract function init();

}