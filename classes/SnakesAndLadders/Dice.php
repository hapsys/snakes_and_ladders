<?php
namespace SnakesAndLadders;

class Dice {
	
	private $minDiceLine = 1;
	private $maxDiceLine = 6;
	private $diceLine = 0;
	
	public function __construct($minDiceLine = null, $maxDiceLine = null) {
		$this->minDiceLine = (int)$minDiceLine?(int)$minDiceLine:$this->minDiceLine;
		$this->maxDiceLine = (int)$maxDiceLine?(int)$maxDiceLine:$this->maxDiceLine;
		srand();
	}
	
	/**
	 * Make dice rool 
	 */
	public function roll() {
		$this->diceLine = rand($this->minDiceLine,$this->maxDiceLine);
	}
	
	/**
	 * @return number
	 */
	public function getDiceLine() {
		return $this->diceLine;
	}
	
}

