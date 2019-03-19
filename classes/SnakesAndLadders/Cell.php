<?php

namespace SnakesAndLadders;

class Cell {
	
	/**
	 * @var integer
	 */
	private $position = 0;
	
	/**
	 * @param int $position
	 */
	public function __construct(int $position) {
		$this->position = $position;
	}
	
	/**
	 * @return number
	 * Get cureent cell position
	 */
	public function getPosition() {
		return $this->position;
	}
	
	/**
	 * @return boolean
	 * Check sanke
	 */
	public function isSnake() {
		return ($this->position % 9)?false:true;
	}

	/**
	 * @return boolean
	 * Check ladder
	 */
	public function isLadder() {
		return $this->position === 25 || $this->position === 55; 
	}
		
	/**
	 * @return boolean
	 * Check overflow
	 */
	public function isWrong() {
		return $this->position > 100;
	}

	/**
	 * @return boolean
	 * Check last cell
	 */
	public function isFinished() {
		return $this->position === 100;
	}
	
}

