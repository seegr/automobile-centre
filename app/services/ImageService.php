<?php


namespace App\Services;


class ImageService
{
    const POSITION_TOP_LEFT = 'tl';
    const POSITION_TOP_CENTER = 'tc';
    const POSITION_TOP_RIGHT = 'tr';
    const POSITION_BOTTOM_LEFT = 'bl';
    const POSITION_BOTTOM_CENTER = 'bc';
    const POSITION_BOTTOM_RIGHT = 'br';
    const POSITION_CENTER = 'cc';
    const POSITION_CENTER_LEFT = 'cl';
    const POSITION_CENTER_RIGHT = 'cr';

    public array $positions = [
        self::POSITION_TOP_LEFT => 'Vlevo nahoře',
        self::POSITION_TOP_CENTER => 'Uprostřed nahoře',
        self::POSITION_TOP_RIGHT => 'Vpravo nahoře',
        self::POSITION_BOTTOM_LEFT => 'Vlevo dole',
        self::POSITION_BOTTOM_CENTER => 'Uprostřed dole',
        self::POSITION_BOTTOM_RIGHT => 'Vpravo dole',
        self::POSITION_CENTER => 'Uprostřed',
        self::POSITION_CENTER_LEFT => 'Uprostřed vlevo',
        self::POSITION_CENTER_RIGHT => 'Uprostřed vpravo',
    ];
}