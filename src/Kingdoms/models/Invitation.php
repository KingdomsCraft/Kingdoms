<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 05/08/2016
 * Time: 18:36
 */

namespace Kingdoms\models;

use Kingdoms\KingdomsPlayer;
use Kingdoms\models\guild\Guild;

class Invitation {

    /** @var float */
    private $sentTime;

    /** @var KingdomsPlayer */
    private $sender;

    /** @var KingdomsPlayer */
    private $receiver;

    /** @var Guild */
    private $guild;

    /**
     * Invitation constructor.
     *
     * @param KingdomsPlayer $sender
     * @param KingdomsPlayer $receiver
     * @param Guild $guild
     */
    public function __construct(KingdomsPlayer $sender, KingdomsPlayer $receiver, Guild $guild) {
        $this->sentTime = floor(microtime(true));
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->guild = $guild;
    }

    /**
     * Return sentTime float
     *
     * @return float
     */
    public function getSentTime() {
        return $this->sentTime;
    }

    /**
     * Return invitation guild
     *
     * @return Guild
     */
    public function getGuild() {
        return $this->guild;
    }

    /**
     * Return invitation sender
     *
     * @return KingdomsPlayer
     */
    public function getSender() {
        return $this->sender;
    }

    /**
     * Return invitation receiver
     *
     * @return KingdomsPlayer
     */
    public function getReceiver() {
        return $this->receiver;
    }

    /**
     * Accept a invitation
     */
    public function accept() {
        if(!$this->receiver->gotGuild()) {
            $this->receiver->setGuild($this->guild);
            $this->receiver->sendKingdomMessage("INVITATION_ACCEPTED");
        }
        else {
            $this->cancel();
            $this->receiver->sendKingdomMessage("INVITATION_FAILED");
        }
    }

    /**
     * Cancel the invitation
     */
    public function cancel() {
        $this->receiver->cancelInvitation($this);
    }

}