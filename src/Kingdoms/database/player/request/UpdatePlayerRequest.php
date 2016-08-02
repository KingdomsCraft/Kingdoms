<?php
/**
 * Created by PhpStorm.
 * User: AndrewBit
 * Date: 02/08/2016
 * Time: 11:17
 */

namespace Kingdoms\database\player\request;

use Kingdoms\database\mysql\MySQLRequest;
use Kingdoms\database\player\PlayerDatabase;

class UpdatePlayerRequest extends MySQLRequest {

    // Statuses
    const MYSQL_CONNECTION_ERROR = 0;
    const MYSQL_ERROR = 1;
    const MYSQL_SUCCESS = 2;

    /** @var string */
    private $name;

    /**
     * UpdatePlayerRequest constructor.
     *
     * @param PlayerDatabase $database
     * @param string $name
     */
    public function __construct(PlayerDatabase $database, $name) {
        parent::__construct($database->getCredentials());
        $this->name = strtolower($name);
    }

    public function onRun() {
        $database = $this->getDatabase();
        if($database->connect_error) {
            $this->setResult([self::MYSQL_CONNECTION_ERROR, $database->connect_error]);
        }
        else {
            //ToDo: $database->query("\n");
            if($database->affected_rows > 0) {
                $this->setResult([self::MYSQL_SUCCESS]);
            }
            else {
                $this->setResult([self::MYSQL_ERROR]);
            }
        }
        $database->close();
    }

}