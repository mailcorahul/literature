<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Exception;

class GameControllerTest extends AbstractControllerTest
{
    public function testStartAction()
    {
        $client = static::createClient();

        $client->request("GET", "/game/start");
        $res = $client->getResponse();

        $expected = [
            "success"  => true,
            "response" => [
                "game" => [
                    "id"     => "TF_SOMETHING",
                    "status" => "TF_SOMETHING",
                    "u1"     => "TF_SOMETHING",
                    "u2"     => null,
                    "u3"     => null,
                    "u4"     => null
                ],
                "user" => [
                    "id"    => "TF_SOMETHING",
                    "cards" => "TF_SOMETHING"
                ]
            ]
        ];

        $resBody = self::makeFirstAssertions($res, 200, $expected);
        $this->assertEquals(12, count($resBody->response->user->cards));

        // On reloading the same page, should throw 400 saying you're already in a game
        $client->reload();
        $res = $client->getResponse();

        $expected = [
            "success"   => false,
            "errorCode" => Exception\Code::BAD_REQUEST,
            "errorMessage" => "You are already in an active game",
            "extra"  => [
                "gameId" => $resBody->response->game->id
            ]
        ];

        $resBody = self::makeFirstAssertions($res, 400, $expected);
    }

    public function testIndexAction()
    {
        $client = static::createClient();

        $client->request('GET', '/game');
        $res = $client->getResponse();

        $expected = [
            "success"      => false,
            "errorCode"    => Exception\Code::NOT_FOUND,
            "errorMessage" => "Game not found",
            "extra"        => []
        ];

        $resBody = self::makeFirstAssertions($res, 404, $expected);

        // Now after creating a game
        $client->request('GET', '/game/start');
        $res = $client->getResponse();
        $resBody = self::makeFirstAssertions($res, 200, []);
        $gameId  = $resBody->response->game->id;
        $userId  = $resBody->response->user->id;

        $client->request('GET', '/game');
        $res = $client->getResponse();

        $expected = [
            "success"  => true,
            "response" => [
                "game" => [
                    "id"     => $gameId,
                    "status" => "TF_SOMETHING",
                    "u1"     => $userId,
                    "u2"     => null,
                    "u3"     => null,
                    "u4"     => null
                ],
                "user" => [
                    "id"    => $userId,
                    "cards" => "TF_SOMETHING"
                ]
            ]
        ];

        $resBody = self::makeFirstAssertions($res, 200, $expected);
        $this->assertEquals(12, count($resBody->response->user->cards));
    }
}
