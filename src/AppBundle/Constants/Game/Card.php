<?php

namespace AppBundle\Constants\Game;

/**
*
*/
class Card
{

    const CLUB_1     = "C1";
    const CLUB_2     = "C2";
    const CLUB_3     = "C3";
    const CLUB_4     = "C4";
    const CLUB_5     = "C5";
    const CLUB_6     = "C6";
    const CLUB_7     = "C7";
    const CLUB_8     = "C8";
    const CLUB_9     = "C9";
    const CLUB_10    = "C10";
    const CLUB_11    = "C11";
    const CLUB_12    = "C12";
    const CLUB_13    = "C13";

    const SPADE_1    = "S1";
    const SPADE_2    = "S2";
    const SPADE_3    = "S3";
    const SPADE_4    = "S4";
    const SPADE_5    = "S5";
    const SPADE_6    = "S6";
    const SPADE_7    = "S7";
    const SPADE_8    = "S8";
    const SPADE_9    = "S9";
    const SPADE_10   = "S10";
    const SPADE_11   = "S11";
    const SPADE_12   = "S12";
    const SPADE_13   = "S13";

    const DIAMOND_1  = "D1";
    const DIAMOND_2  = "D2";
    const DIAMOND_3  = "D3";
    const DIAMOND_4  = "D4";
    const DIAMOND_5  = "D5";
    const DIAMOND_6  = "D6";
    const DIAMOND_7  = "D7";
    const DIAMOND_8  = "D8";
    const DIAMOND_9  = "D9";
    const DIAMOND_10 = "D10";
    const DIAMOND_11 = "D11";
    const DIAMOND_12 = "D12";
    const DIAMOND_13 = "D13";


    const HEART_1    = "H1";
    const HEART_2    = "H2";
    const HEART_3    = "H3";
    const HEART_4    = "H4";
    const HEART_5    = "H5";
    const HEART_6    = "H6";
    const HEART_7    = "H7";
    const HEART_8    = "H8";
    const HEART_9    = "H9";
    const HEART_10   = "H10";
    const HEART_11   = "H11";
    const HEART_12   = "H12";
    const HEART_13   = "H13";

    const LOWER_RANGE  = "lower";
    const HIGHER_RANGE = "higher";


    // Doesn't include card 7
    public static $allInGame = [
        self::CLUB_1,
        self::CLUB_2,
        self::CLUB_3,
        self::CLUB_4,
        self::CLUB_5,
        self::CLUB_6,
        self::CLUB_8,
        self::CLUB_9,
        self::CLUB_10,
        self::CLUB_11,
        self::CLUB_12,
        self::CLUB_13,

        self::SPADE_1,
        self::SPADE_2,
        self::SPADE_3,
        self::SPADE_4,
        self::SPADE_5,
        self::SPADE_6,
        self::SPADE_8,
        self::SPADE_9,
        self::SPADE_10,
        self::SPADE_11,
        self::SPADE_12,
        self::SPADE_13,

        self::DIAMOND_1,
        self::DIAMOND_2,
        self::DIAMOND_3,
        self::DIAMOND_4,
        self::DIAMOND_5,
        self::DIAMOND_6,
        self::DIAMOND_8,
        self::DIAMOND_9,
        self::DIAMOND_10,
        self::DIAMOND_11,
        self::DIAMOND_12,
        self::DIAMOND_13,

        self::HEART_1,
        self::HEART_2,
        self::HEART_3,
        self::HEART_4,
        self::HEART_5,
        self::HEART_6,
        self::HEART_8,
        self::HEART_9,
        self::HEART_10,
        self::HEART_11,
        self::HEART_12,
        self::HEART_13,
    ];
}
