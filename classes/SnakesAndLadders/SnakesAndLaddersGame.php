<?php

namespace SnakesAndLadders;

class SnakesAndLaddersGame {
	
	/**
	 * @var GameStep
	 */
	private $gameStep; 
	
	
	/**
	 * 
	 */
	public function __construct() {
		$this->gameStep = new GameStep(new Cell(1), new Dice(1,6));
	}
	
	
	/**
	 * Start game 
	 */
	public function start() {
		while($this->gameStep->next()) {
			sleep(1);
		}
	}
	
	
}

