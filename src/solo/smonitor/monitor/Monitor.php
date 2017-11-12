<?php

namespace solo\smonitor\monitor;

abstract class Monitor{

	const DEFAULT_GRAPH_ROW = 128;
	const DEFAULT_GRAPH_COLUMN = 5;

	private $graphRow = self::DEFAULT_GRAPH_ROW;
	private $graphColumn = self::DEFAULT_GRAPH_COLUMN;

	private $log = [];

	private $averageValue = 0;
	private $maxValue = 0;

	abstract public function getName();

	abstract public function update();

	public function getColor(){
		return '§a';
	}

	public function getGraphCharacter(){
		return '|';
	}

	public function getGraphRow(){
		return $this->graphRow;
	}

	public function getGraphColumn(){
		return $this->graphColumn;
	}

	public function setGraphRow(int $row){
		$this->graphRow = $row;
	}

	public function setGraphColumn(int $column){
		$this->graphColumn = $column;
	}

	public function push($value){
		array_unshift($this->log, $value);
		if(count($this->log) > $this->graphRow){
			array_pop($this->log);
		}
		$this->averageValue = array_sum($this->log) / count($this->log);
		$this->maxValue = max($this->log);
	}

	public function get(int $index){
		return $this->log[$index] ?? null;
	}

	public function getLatest(){
		return $this->log[0];
	}

	public function getAverage(){
		return $this->averageValue;
	}

	public function getMax(){
		return $this->maxValue;
	}

	public function getGraphMaxHeight(){
		$maxValue = $this->getMax();
		foreach([10, 20, 30, 50, 80, 100, 150, 200, 300, 500, 800, 1000, 1500, 2000, 3000, 5000, 8000, 10000] as $flexibleHeight){
			if($maxValue < $flexibleHeight){
				return $flexibleHeight;
			}
		}
	}

	public function createGraph(){
		$maxHeight = max(5, $this->getGraphMaxHeight());

		$lines = [];
		for($i = 0; $i < $this->graphColumn; ++$i){
			$sideMeter = intval($maxHeight / $this->graphColumn) * ($i + 1);
			if(strlen($sideMeter) < strlen($maxHeight)){
				$sideMeter = '§0' . str_repeat('0', strlen($maxHeight) - strlen($sideMeter)) . $this->getColor() . $sideMeter;
			}else{
				$sideMeter = $this->getColor() . $sideMeter;
			}
			$lines[$i] = $sideMeter . "§7::";
		}
		$latestColor = array_pad([], $this->graphColumn, '');
		for($i = 0; $i < $this->graphRow; ++$i){
			$elementValue = $this->get($this->graphRow - $i) ?? 0;
			$elementHeight = ($elementValue == 0) ? 0 : round(($elementValue / $maxHeight) * $this->graphColumn);
			for($ei = 0; $ei < $this->graphColumn; ++$ei){
				$color = ($elementHeight == $maxHeight || $elementHeight > $ei) ? $this->getColor() : '§0';
				if($latestColor[$ei] == $color){
					$lines[$ei] .= $this->getGraphCharacter();
				}else{
					$lines[$ei] .= $color . $this->getGraphCharacter();
					$latestColor[$ei] = $color;
				}
			}
		}
		return implode("\n", array_reverse($lines));
	}
}
