<?php

/**
 * Kingdoms Craft Kingdoms
 *
 * Copyright (C) 2016 Kingdoms Craft
 *
 * This is private software, you cannot redistribute it and/or modify any way
 * unless otherwise given permission to do so. If you have not been given explicit
 * permission to view or modify this software you should take the appropriate actions
 * to remove this software from your device immediately.
 *
 * @author JackNoordhuis
 */

namespace kingdoms\command;

use kingdoms\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

abstract class KingdomsCommand extends Command {

	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin, $name, $description, $usageMessage, $aliases) {
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @return Main
	 */
	public function getPlugin() {
		return $this->plugin;
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 *
	 * @return bool
	 */
	public function execute(CommandSender $sender, $commandLabel, array $args) {
		if($this->testPermission($sender)) {
			return $this->run($sender, $args);
		} else {
			$sender->sendMessage($this->getPermissionMessage());
			return true;
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function run(CommandSender $sender, array $args);

}