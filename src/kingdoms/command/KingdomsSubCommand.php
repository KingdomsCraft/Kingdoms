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

abstract class KingdomsSubCommand {

	/** @var KingdomsCommand */
	private $command;

	/** @var string */
	protected $name;

	/** @var string */
	protected $usage;

	public function __construct(KingdomsCommand $command, $name, $usage) {
		$this->command = $command;
		$this->name = $name;
		$this->usage = $usage;
	}

	/**
	 * @return \kingdoms\Main
	 */
	public function getPlugin() {
		return $this->command->getPlugin();
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 *
	 * @return bool
	 */
	public abstract function execute(CommandSender $sender, array $args = []);

}