<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 15:24
 */

namespace Kingdoms\models\kingdom;

use Kingdoms\Base;

class KingdomManager {

    /** @var Base */
    private $plugin;

    /** @var Kingdom[] */
    private $kingdoms = [];

    /**
     * KingdomManager constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    private function init() {
        $this->plugin->getDatabaseManager()->initializeKingdoms();
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
     * Return kingdoms
     *
     * @return Kingdom[]
     */
    public function getKingdoms() {
        return $this->kingdoms;
    }

    /**
     * @param string $kingdom
     * @return null|Kingdom
     */
    public function getKingdom($kingdom) {
        if(isset($this->kingdoms[$kingdom])) {
            return $kingdom;
        }
        else {
            $this->plugin->getLogger()->critical("Couldn't get kingdom {$kingdom} due it's not registered! Please, contact me (@AndrewBit4) to check what is happening!");
            return null;
        }
    }

    /**
     * Load a new Kingdom
     *
     * @param string $name
     * @param int $points
     * @param string $motto
     * @param int $wonWars
     * @param int $lostWars
     * @param string $home
     */
    public function loadFaction($name, $points, $motto, $wonWars, $lostWars, $home) {
        $this->kingdoms[$name] = new Kingdom($this->plugin, $name, $points, $motto, $wonWars, $lostWars, $home);
    }

    public function updateName($oldName, $newName) {
        $logger = $this->plugin->getLogger();
        if(isset($this->kingdoms[$oldName])) {
            $kingdom = $this->kingdoms[$oldName];
            unset($this->kingdoms[$oldName]);
            $this->kingdoms[$newName] = $kingdom;
            $logger->info("{$oldName} kingdom was renamed to {$newName}");
        }
        else {
            $logger->critical("Couldn't rename {$oldName} to {$newName} due the kingdom is not loaded!");
        }
    }

}