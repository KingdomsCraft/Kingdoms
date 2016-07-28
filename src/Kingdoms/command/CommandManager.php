<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 14:28
 */

namespace Kingdoms\command;

use Kingdoms\Base;

class CommandManager {

    /** @var Base */
    private $plugin;

    /** @var array */
    private $commands = [];

    /**
     * CommandManager constructor.
     * @param Base $plugin
     */
    public function __construct(Base $plugin) {
        $this->plugin = $plugin;
        $this->init();
    }

    public function init() {
        $this->commands["kingdom"] = new KingdomCommand($this->plugin);
        foreach($this->commands as $key => $command) {
            $this->plugin->getServer()->getCommandMap()->register($key, $command);
        }
    }

}