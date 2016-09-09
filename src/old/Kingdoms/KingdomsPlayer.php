<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 29/07/2016
 * Time: 17:11
 */

namespace Kingdoms;

use Kingdoms\language\LanguageManager;
use Kingdoms\models\guild\Guild;
use Kingdoms\models\Invitation;
use Kingdoms\models\kingdom\Kingdom;
use pocketmine\Player;

class KingdomsPlayer extends Player {

	const KINGDOM_RANK_CITIZEN = 0;
	const KINGDOM_RANK_NOBLEMAN = 1;

	/* Ranges */
	const KINGDOM_RANK_KING = 2;
	/** @var Kingdom|null */
	private $kingdom = null;
	/** @var int */
	private $kingdomRank = 0;
	/** @var Guild|null */
	private $guild = null;

	/** @var bool */
	private $leader = false;

	/** @var bool */
	private $admin = false;

	/** @var Invitation */
	private $lastInvitation = null;

	/** @var Invitation[] */
	private $invitations = [];

	/**
	 * Return player data
	 *
	 * @return array
	 */
	public function getPlayerData() {
		$kingdom = ($this->gotKingdom()) ? $this->getKingdom()->getName() : "No kingdom";
		$guild = ($this->gotGuild()) ? $this->getGuild()->getName() : "No guild";
		return ["kingdom" => $kingdom, "guild" => $guild];
	}

	/**
	 * Return true if a player got kingdom, false if not
	 *
	 * @return bool
	 */
	public function gotKingdom() {
		return $this->kingdom instanceof Kingdom;
	}

	/**
	 * Return player kingdom
	 *
	 * @return Kingdom|null
	 */
	public function getKingdom() {
		return $this->kingdom;
	}

	/**
	 * Set player kingdom
	 *
	 * @param Kingdom|null $kingdom
	 */
	public function setKingdom($kingdom) {
		$this->kingdom = $kingdom;
	}

	/**
	 * Return true if a player got guild, false if not
	 *
	 * @return bool
	 */
	public function gotGuild() {
		return $this->guild instanceof Kingdom;
	}

	/**
	 * Return player guild
	 *
	 * @return Guild|null
	 */
	public function getGuild() {
		return $this->guild;
	}

	/**
	 * Set player guild
	 *
	 * @param Guild|null $guild
	 */
	public function setGuild($guild) {
		$this->guild = $guild;
	}

	/**
	 * Return if player rank is superior
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function isRankSuperior($id) {
		if($this->kingdomRank - $id >= 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Return player kingdom rank
	 *
	 * @return int
	 */
	public function getKingdomRank() {
		return $this->kingdomRank;
	}

	/**
	 * Set player kingdom rank
	 *
	 * @param int $rankId
	 */
	public function setKingdomRank($rankId) {
		$this->kingdomRank = $rankId;
	}

	/**
	 * Return player last guild invitation
	 *
	 * @return Invitation
	 */
	public function getLastInvitation() {
		return $this->lastInvitation;
	}

	/**
	 * Set the last invitation
	 *
	 * @param Invitation $invitation
	 */
	public function setLastInvitation(Invitation $invitation) {
		$this->lastInvitation = $invitation;
	}

	/**
	 * Return player invitations
	 *
	 * @return Invitation[]
	 */
	public function getInvitations() {
		return $this->invitations;
	}

	/**
	 * Get an invitation from a guild
	 *
	 * @param Guild $guild
	 *
	 * @return Invitation|null
	 */
	public function getInvitation(Guild $guild) {
		if($this->isInvitation($guild)) {
			return $this->invitations[$guild->getName()];
		} else {
			return null;
		}
	}

	/**
	 * Check if a invitation exists
	 *
	 * @param Guild $guild
	 *
	 * @return bool
	 */
	public function isInvitation(Guild $guild) {
		return isset($this->invitations[$guild->getName()]);
	}

	/**
	 * Return if the player is admin
	 *
	 * @return bool
	 */
	public function isAdmin() {
		return $this->admin;
	}

	/**
	 * Set a player admin
	 *
	 * @param bool $bool
	 */
	public function setAdmin($bool = true) {
		$this->admin = $bool;
	}

	/**
	 * Return if the player is leader (referring to the guild)
	 *
	 * @return bool
	 */
	public function isLeader() {
		return $this->leader;
	}

	/**
	 * Set a player leader of his guild
	 *
	 * @param bool $bool
	 */
	public function setLeader($bool = true) {
		$this->leader = $bool;
	}

	/**
	 * Add an invitation to the player
	 *
	 * @param KingdomsPlayer $sender
	 * @param Guild $guild
	 */
	public function addInvitation(KingdomsPlayer $sender, Guild $guild) {
		$invitation = new Invitation($sender, $this, $guild);
		$this->invitations[$guild->getName()] = $invitation;
		$this->setLastInvitation($invitation);
	}

	/**
	 * Cancel an invitation
	 *
	 * @param Invitation $invitation
	 *
	 * @return bool
	 */
	public function cancelInvitation(Invitation $invitation) {
		if($this->isInvitation($invitation->getGuild())) {
			unset($this->invitations[$invitation->getGuild()->getName()]);
			$this->reloadInvitations();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Reload all invitations
	 */
	public function reloadInvitations() {
		$times = [];
		foreach($this->invitations as $invitation) {
			$times[(int)round(microtime(true) - $invitation->getSentTime())] = $invitation;
		}
		ksort($times);
		$this->lastInvitation = (isset($times[0])) ? $times[0] : null;
	}

	/**
	 * Send a message by key
	 *
	 * @param string $key
	 */
	public function sendKingdomMessage($key) {
		$message = LanguageManager::getInstance()->getMessage($key);
		if($this->gotKingdom()) {
			$message = str_replace("%kingdom%", $this->getKingdom()->getName(), $message);
		}
		if($this->gotGuild()) {
			$message = str_replace("%guild%", $this->getGuild()->getName(), $message);
		}
		$this->sendMessage($message);
	}

	/**
	 * Send a message with a kingdom position in the leaderboard
	 *
	 * @param $rank
	 * @param string $kingdom
	 * @param $points
	 */
	public function sendRankedKingdom($rank, $kingdom, $points) {
		$message = LanguageManager::getInstance()->getMessage("KINGDOM_RANK");
		$message = str_replace("{rank}", $rank, $message);
		$message = str_replace("{name}", $kingdom, $message);
		$message = str_replace("{points}", $points, $message);
		$this->sendMessage($message);
	}

	/**
	 * Send message with amount of pages
	 *
	 * @param $page
	 * @param $maxPages
	 */
	public function sendPageAmount($page, $maxPages) {
		$message = LanguageManager::getInstance()->getMessage("KINGDOM_TOP_PAGES");
		$message = str_replace("{page}", $page, $message);
		$message = str_replace("{maxPages}", $maxPages, $message);
		$this->sendMessage($message);
	}

	/**
	 * Send kingdom info
	 *
	 * @param $name
	 * @param $motto
	 * @param $points
	 * @param $leader
	 * @param $warsWon
	 * @param $rank
	 * @param $citizens
	 */
	public function sendKingdomInfo($name, $motto, $points, $leader, $warsWon, $rank, $citizens) {
		$message = LanguageManager::getInstance()->getMessage("KINGDOM_INFO_HEADER");
		$message = str_replace("{name}", $name, $message);
		$this->sendMessage($message);
		$message = LanguageManager::getInstance()->getMessage("KINGDOM_INFO");
		$message = str_replace("{name}", $name, $message);
		$message = str_replace("{motto}", $motto, $message);
		$message = str_replace("{points}", $points, $message);
		$message = str_replace("{leader}", $leader, $message);
		$message = str_replace("{warsWon}", $warsWon, $message);
		$message = str_replace("{rank}", $rank, $message);
		$message = str_replace("{citizens}", $citizens, $message);
		$this->sendMessage($message);
	}

	/**
	 * Update the player
	 */
	public function update() {
		Main::getInstance()->getPluginDatabase()->getPlayerDatabase()->updatePlayer($this->getName());
	}

}