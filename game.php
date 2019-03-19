<?php


use SnakesAndLadders\SnakesAndLaddersGame;

include_once './autoload.php';

new AutoloadDefault('classes/');

//$dice = new Dice(1,6);

$game = new SnakesAndLaddersGame();

$game->start();
