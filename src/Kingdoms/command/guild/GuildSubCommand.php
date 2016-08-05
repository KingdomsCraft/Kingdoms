<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 04/08/2016
 * Time: 12:59
 */

namespace Kingdoms\command\guild;


use pocketmine\command\CommandSender;

interface GuildSubCommand {

    /**
     * @param CommandSender $sender
     * @param array $args
     */
    public function execute(CommandSender $sender, $args);

}