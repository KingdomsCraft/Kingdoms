<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 20:37
 */

namespace Kingdoms\command;

use Kingdoms\Main;
use pocketmine\command\Command;

class CommandManager {

    /** @var Main */
    private $plugin;

    /** @var Command[] */
    private $commands = [];

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
     * Initializes the plugin
     */
    private function init() {
        $this->commands["kingdom"] = new KingdomCommand($this->plugin);
        $this->commands["guild"] = new GuildCommand($this->plugin);
        $commandMap = $this->plugin->getServer()->getCommandMap();
        foreach($this->commands as $name => $command) {
            $commandMap->register($name, $command);
        }
    }

    /**
     * Return commands
     *
     * @return Command[]
     */
    public function getCommands() {
        return $this->commands;
    }

}