<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:30
 */

namespace Kingdoms\command;

use Kingdoms\command\kingdom\CreateCommand;
use Kingdoms\command\kingdom\HelpCommand;
use Kingdoms\command\kingdom\HomeCommand;
use Kingdoms\command\kingdom\InfoCommand;
use Kingdoms\command\kingdom\KingdomSubCommand;
use Kingdoms\command\kingdom\ListCommand;
use Kingdoms\command\kingdom\SetHomeCommand;
use Kingdoms\command\kingdom\TopCommand;
use Kingdoms\KingdomsPlayer;
use Kingdoms\Main;

class CommandManager {

    /** @var Main */
    private $plugin;

    /** @var KingdomCommand */
    private $kingdomCommand;

    /** @var KingdomSubCommand[] */
    private $kingdomCommands = [];

    /**
     * CommandManager constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    /**
     * Initialize the class
     */
    public function init() {
        $this->kingdomCommand = new KingdomCommand($this);
        $this->kingdomCommands["help"] = new HelpCommand($this);
        $this->kingdomCommands["create"] = new CreateCommand($this);
        $this->kingdomCommands["top"] = new TopCommand($this);
        $this->kingdomCommands["info"] = new InfoCommand($this);
        $this->kingdomCommands["home"] = new HomeCommand($this);
        $this->kingdomCommands["sethome"] = new SetHomeCommand($this);
        $this->kingdomCommands["list"] = new ListCommand($this);
        $this->registerAll();
    }

    /**
     * Register all commands
     */
    public function registerAll() {
        $commandMap = $this->plugin->getServer()->getCommandMap();
        $commandMap->register("kingdom", $this->kingdomCommand);
    }

    /**
     * Execute a Kingdom subcommand
     *
     * @param string $command
     * @param KingdomsPlayer $sender
     * @param array $args
     */
    public function kingdom_execute($command, KingdomsPlayer $sender, $args) {
        unset($args[0]);
        $args = implode(" ", $args);
        $args = explode(" ", $args);
        $this->kingdomCommands[$command]->execute($sender, $args);
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
     * Return KingdomSubCommand array
     *
     * @return KingdomSubCommand[]
     */
    public function getKingdomCommands() {
        return $this->kingdomCommands;
    }

}