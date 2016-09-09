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

use pocketmine\command\CommandSender;

abstract class KingdomsUniversalCommand extends KingdomsCommand {

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public function run(CommandSender $sender, array $args) {
		return $this->onRun($sender, $args);
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return mixed
	 */
	public abstract function onRun(CommandSender $sender, array $args);

}