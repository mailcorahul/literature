<?php

namespace AppBundle\Model\Redis;

use AppBundle\Utility;
use AppBundle\Constant\Game\Status;
use AppBundle\Constant\Game\Game as GameK;
use AppBundle\Exception\BadRequestException;

class Game
{
    public $id;
    public $createdAt;
    public $status;
    public $prevTurn;
    public $prevTurnTimeStamp;
    public $nextTurn;

    // @codingStandardsIgnoreStart
    // User serial numbers
    public $u1;
    public $u2;
    public $u3;
    public $u4;

    public $u1Points;
    public $u2Points;
    public $u3Points;
    public $u4Points;

    // Initial cards of all users
    public $u1Cards;
    public $u2Cards;
    public $u3Cards;
    public $u4Cards;
    // @codingStandardsIgnoreStart

    public $teams;

    public function __construct(
        string $id,
        array  $params
    )
    {

        $this->id = $id;

        // Sets all attributes of the object
        foreach ($params as $key => $value)
        {
            $property        = Utility::camelizeLcFirst($key);
            $this->$property = $value;
        }

        // Sets teams
        $this->teams = [
            GameK::TEAM_1 => [$this->u1, $this->u3],
            GameK::TEAM_2 => [$this->u2, $this->u4],
        ];
    }

    //
    // Getters

    public function isActive()
    {
        return ($this->status === Status::ACTIVE);
    }

    public function isExpired()
    {
        return ($this->status === Status::EXPIRED);
    }

    public function isNotExpired()
    {
        return !($this->isExpired());
    }

    public function isSNVacant(
        string $userSN
    )
    {
        // Checks if given serial number is vacant for new users

        if (property_exists($this, $userSN) === false)
        {
            throw new BadRequestException('Invalid user serial number');
        }

        return ($this->$userSN == null);
    }

    public function isAnySNVacant()
    {
        // Checks if any serial number is vacant at all in the game

        return ($this->u1 == null ||
                $this->u2 == null ||
                $this->u3 == null ||
                $this->u4 == null);
    }

    public function hasUser(
        string $userId
    )
    {

        return in_array(
            $userId,
            [
                $this->u1,
                $this->u2,
                $this->u3,
                $this->u4,
            ],
            true
        );
    }

    public function areTeam(
        string $userId1,
        string $userId2
    )
    {

        // Checks if given two users are team

        $x = in_array($userId1, $this->teams[GameK::TEAM_1], true);
        $y = in_array($userId2, $this->teams[GameK::TEAM_1], true);

        return ($x === $y);
    }

    public function getSNByUserId(
        string $userId
    )
    {
        switch ($userId)
        {
            case $this->u1:
                return GameK::U1;
                break;

            case $this->u2:
                return GameK::U2;
                break;

            case $this->u3:
                return GameK::U3;
                break;

            case $this->u4:
                return GameK::U4;
                break;

            default:
                return null;
                break;
        }
    }

    public function getNextTurnUserId()
    {
        $nextTurnSN = $this->nextTurn;

        return $this->$nextTurnSN;
    }

    public function getInitCardsBySN(
        string $userSN
    )
    {

        $attribute = $userSN . 'Cards';

        if (empty($this->$attribute))
        {
            return [];
        }
        else
        {
            return explode(',', $this->$attribute);
        }
    }

    public function getTeamUsers(
        string $team
    )
    {
        return $this->teams[$team];
    }

    public function getTeam(
        string $userId
    )
    {
        // Returns team of given user id

        if (in_array($userId, $this->teams[GameK::TEAM_1]))
        {
            return GameK::TEAM_1;
        }
        else
        {
            return GameK::TEAM_2;
        }
    }

    public function getOppTeam(
        string $userId
    )
    {
        // Returns opposite team for given user id

        if (in_array($userId, $this->teams[GameK::TEAM_1]))
        {
            return GameK::TEAM_2;
        }
        else
        {
            return GameK::TEAM_1;
        }
    }

    public function toArray()
    {

        return [
            'id'        => $this->id,
            'createdAt' => $this->createdAt,
            'status'    => $this->status,

            'u1'        => $this->u1,
            'u2'        => $this->u2,
            'u3'        => $this->u3,
            'u4'        => $this->u4,
        ];
    }

    //
    // Setters

    public function incrPoint(
        string $userId,
        float  $point
    )
    {
        // Increments points by given amount for given user

        $property = $this->getSNByUserId($userId) . "Points";

        $this->$property += $point;

        return $this;
    }

}
