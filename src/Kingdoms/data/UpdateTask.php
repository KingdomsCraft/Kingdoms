<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 24/07/2016
 * Time: 21:27
 */

namespace Kingdoms\database;

use Kingdoms\Base;
use Kingdoms\KingdomPlayer;
use pocketmine\scheduler\PluginTask;

class UpdateTask extends PluginTask {

    /** @var DatabaseManager */
    private $databaseManager;

    /**
     * UpdateTask constructor.
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager) {
        parent::__construct($databaseManager->getPlugin());
    }

    /**
     * @param int $currentTick
     */
    public function onRun($currentTick) {
        /** @var Base $owner */
        $owner = $this->getOwner();
        $kingdomManager = $owner->getKingdomManager();
        $guildManager = $owner->getGuildManager();
        foreach($kingdomManager->getKingdoms() as $name => $kingdom) {
            $this->databaseManager->updateKingdom($name);
            if($name != $kingdom->getName()) {
                $kingdomManager->updateName($name, $kingdom->getName());
            }
        }
        foreach($guildManager->getGuilds() as $name => $guild) {
            $this->databaseManager->updateGuild($name);
            if($name != $guild->getName()) {
                $guildManager->updateName($name, $guild->getName());
            }
        }
        /** @var KingdomPlayer $player */
        foreach($owner->getServer()->getOnlinePlayers() as $player) {
            $player->update();
        }
    }
}