<?php

namespace SnakesAndLadders;

class GameStep {
	
	/**
	 * @var Cell
	 */
	private $currentCell;
	/**
	 * @var Dice
	 */
	private $dice;
	
	/**
	 * @var string
	 */
	private $additional = '';
	
	/**
	 * @param Cell $cell
	 * @param Dice $dice
	 */
	public function __construct(Cell $cell, Dice $dice) {
		$this->currentCell = $cell;
		$this->dice = $dice;
	}
	
	/**
	 * @return boolean
	 * Make next game step
	 */
	public function next() {
		$continue = true;
		
		$this->additional = '';
		
		// roll dice
		$this->dice->roll();
		
		// new cell after roll
		$newCell = new Cell($this->currentCell->getPosition() + $this->dice->getDiceLine());
		
		
		if ($newCell->isFinished()) {
			// cehck if is finish cell
			$this->currentCell = $newCell;
			$continue = false;
		} else if ($newCell->isWrong()) {
			// Do nothing
		} else if ($newCell->isLadder()) {
			// cehck if is ladder cell
			$this->additional = 'ladder';
			$this->currentCell = new Cell($newCell->getPosition() + 10);
		} else if ($newCell->isSnake()) {
			// cehck if is snake cell
			$this->additional = 'snake';
			$this->currentCell = new Cell($newCell->getPosition() - 3);
		} else {
			// normal step
			$this->currentCell = $newCell;
		}
		
		$this->draw();
		
		return $continue;
	}
	
	/**
	 * Out game step info 
	 */
	public function draw() {
		echo $this->dice->getDiceLine(), '-', $this->additional, $this->currentCell->getPosition(), "\n";
	}
}

