<?php
use SnakesAndLadders\SnakesAndLaddersGame;

include_once __DIR__.'/autoload.php';

new AutoloadDefault(__DIR__.'/classes/');

//$dice = new Dice(1,6);

$game = new SnakesAndLaddersGame();

$game->start();
