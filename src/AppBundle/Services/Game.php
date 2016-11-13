<?php

namespace AppBundle\Services;

use AppBundle\Models;

use AppBundle\Exceptions\NotFoundException;
use AppBundle\Exceptions\BadRequestException;

/**
 *
 */
class Game extends BaseService
{

    protected $redis;
    protected $pubSub;
    protected $knowledge;

    /**
     * @param object                          $logger
     * @param object                          $redis
     * @param Services\PubSub\PubSubInterface $pubSub
     * @param Services\Knowledge              $knowledge
     *
     * @return
     */
    public function __construct(
        $logger,
        $redis,
        PubSub\PubSubInterface $pubSub,
        Knowledge $knowledge)
    {
        parent::__construct($logger);

        $this->redis     = $redis;
        $this->pubSub    = $pubSub;
        $this->knowledge = $knowledge;
    }

    /**
     * @param string $gameId
     *
     * @return null|Models\Game
     */
    public function fetchById(
        string $gameId)
    {
        $redisGameResults = $this->redis->hgetall($gameId);

        if (empty($redisGameResults)) {

            return null;
        } else {

            return new Models\Game($gameId, $redisGameResults);
        }
    }

    /**
     * @param Models\Game $game
     *
     * @return null
     */
    public function delete(
        Models\Game $game)
    {
        // TODO
        // - Clean redis data for given gameId
    }

    /**
     * @param string $userId
     *
     * @return null|Models\User
     */
    public function fetchUserById(
        string $userId)
    {
        $redisUserCards = $this->redis->smembers($userId);

        if (empty($redisUserCards)) {

            return null;
        } else {

            return new Models\User($userId, $redisUserCards);
        }
    }

    /**
     * @param string $sessionId
     * @param string $createdAt
     * @param string $userId
     *
     * @return null
     */
    public function initializeGame(
        string $gameId,
        string $createdAt,
        string $userId)
    {
        list($cardU1, $cardU2, $cardU3, $cardU4) = $this->CardDistribution();
        // var_dump($gameId);
        $initializeGameResults = $this->redis->hMset(
            $gameId,
            "created_at",   $createdAt, 
            "total_user" ,  1,
            "u1",           $userId,
            "status",       "active",
            "next_turn",    "u1",
            "u1_card",      implode(" ", $cardU1),
            "u2_card",      implode(" ", $cardU2),
            "u3_card",      implode(" ", $cardU3),
            "u4_card",      implode(" ", $cardU4)
            );
        
        $initializeUserReuslt = $this->redis->sAdd(
            $userId,
            $cardU1
            );

        // TODO: Make user

        return array($this->fetchById($gameId), $this->fetchUserById($userId));

    }

    /**
     * @param string $sessionId
     * @param string $createdAt
     * @param string $userId
     *
     * @return null
     */
    private function cardDistribution() {

        $total_card = array();
        $card_color = ['C', 'D', 'H', 'S'];
        while(count($total_card) < 48) {
            $color = (int)(rand(1,51)/13);
            $card_no = (int)(rand(4,52)/4);
            $card =   $card_color[$color] . $card_no;
            if ( !in_array( $card, $total_card ) && $card_no != 7) {
                array_push($total_card, $card);
            }
        }
        $user1 = array();
        $user2 = array();
        $user3 = array();
        $user4 = array();
        for($i=0; $i<12; $i++) {
            for($j=0; $j<=3; $j++) {
                if($i%4==0){
                    array_push($user1, $total_card[$i*4+$j]);
                }
                if($i%4==1){
                    array_push($user2, $total_card[$i*4+$j]);
                }
                if($i%4==2){
                    array_push($user3, $total_card[$i*4+$j]);
                }
                if($i%4==3){
                    array_push($user4, $total_card[$i*4+$j]);
                }
            }
        }
        return array($user1, $user2, $user3, $user4);
    }

     /*
     * @param Models\Game $game
     * @param string      $userId
     *
     * @return
     */
    public function validateGame(
        Models\Game $game,
        string $userId)
    {
        if (empty($game)) {

            throw new NotFoundException("Game with given id not found.");
        }

        if ($game->isActive() === false) {

            $this->delete($game);

            throw new NotFoundException("Game with given id is no longer active.");
        }

        if ($game->hasUser($userId) === false) {

            throw new BadRequestException("You do not belong to game with given id.");
        }
    }

    /**
     *
     * @param Models\Game $game
     * @param string      $card
     * @param string      $fromUserSN
     * @param string      $toUserSN
     *
     * @return
     */
    public function moveCard(
        Models\Game $game,
        string $card,
        Models\User $fromUser,
        Models\User $toUser)
    {
        if ($game->getNextTurnUserId() !== $toUser->getId()) {
            throw new BadRequestException("It is not your turn to make a move.");
        }

        if ($toUser->hasAtLeastOneOfType($card) === false) {
            throw new BadRequestException("You do not have at least one card of that type. Invalid move.");
        }

        // TODOs:
        // Update data
        // Check game status - Win/Loose etc
        // Publish response data too
        // Ensure $game & $user refreshed
    }
}
