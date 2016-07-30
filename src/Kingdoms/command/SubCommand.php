<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 23:40
 */

namespace Kingdoms\command;

use Kingdoms\Main;

abstract class SubCommand {

    /** @var Main */
    private $plugin;

    /**
     * SubCommand constructor.
     *
     * @param CommandManager $commandManager
     */
    public function __construct(CommandManager $commandManager) {
        $this->plugin = $commandManager->getPlugin();
    }

    /**
     * Return Main instance
     *
     * @return Main
     */
    public function getPlugin() {
        return $this->plugin;
    }

}