<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 30/07/2016
 * Time: 22:38
 */

namespace Kingdoms\command\kingdom;

use pocketmine\command\CommandSender;

interface KingdomSubCommand {

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 */
	public function execute(CommandSender $sender, $args);

}