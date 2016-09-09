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

namespace kingdoms\command\commands;


use kingdoms\command\KingdomsSubCommand;
use kingdoms\command\KingdomsSubCommandHolder;
use kingdoms\command\KingdomsUniversalCommand;
use kingdoms\Main;
use pocketmine\command\CommandSender;

class KingdomCommand extends KingdomsUniversalCommand implements KingdomsSubCommandHolder {

	/** @var KingdomsSubCommand[] */
	protected $subCommands;

	public function __construct(Main $plugin) {
		parent::__construct($plugin, "kingdoms", "Main kingdoms command", "kingdoms <action>", ["k", "kingdom", "fac", "faction", "factions", "f"]);
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isSubCommand($name) {
		return isset($this->subCommands[strtolower($name)]);
	}

	/**
	 * @param string $name
	 *
	 * @return KingdomsSubCommand|null
	 */
	public function getSubCommand($name) {
		if(!$this->isSubCommand($name)) return null;
		return $this->subCommands[strtolower($name)];
	}

	public function onRun(CommandSender $sender, array $args) {
		if(isset($args[0])) {

		} else {
			return false;
		}
	}

}