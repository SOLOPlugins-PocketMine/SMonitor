<?php

namespace solo\servermonitor;

class ServerLog{

	const SIZE = 128;

	const GRAPH_WIDTH = 128;
	const GRAPH_HEIGHT = 5;

	private $name;
	private $color;
	private $style;

	private $log = [];

	public function __construct(string $name, string $color = '§a', string $style = '|'){
		$this->name = $name;
		$this->color = $color;
		$this->style = $style;
	}

	public function getName(){
		return $this->name;
	}

	public function getColor(){
		return $this->color;
	}

	public function getStyle(){
		return $this->style;
	}

	public function push($value){
		array_unshift($this->log, $value);
		if(count($this->log) > self::SIZE){
			array_pop($this->log);
		}
	}

	public function get(int $index){
		return $this->log[$index] ?? null;
	}

	public function getLatest(){

	}

	public function getAverage(){
		return array_sum($this->log) / count($this->log);
	}

	public function getMax(){
		return max($this->log);
	}

	public function getGraphMaxHeight(){
		
	}

	public function setGraphMaxHeight(){

	}

	public function createGraph(){
		$maxValue = $this->getMax();
		$height = ceil($maxValue);
		foreach([5, 10, 20, 30, 50, 80, 100, 150, 200, 300, 500, 800, 1000, 1500, 2000, 3000, 5000, 8000, 10000] as $flexibleHeight){
			if($maxValue < $flexibleHeight){
				$height = $flexibleHeight;
				break;
			}
		}
		$lines = array_pad([], self::GRAPH_HEIGHT, '');
		for($i = 0; $i < self::GRAPH_HEIGHT; ++$i){
			$lines[$i] = '§0' . str_pad($this->color . (intval($height / self::GRAPH_HEIGHT) * $i), '0', strlen($height), STR_PAD_LEFT) . "§7::";
		}
		$lastestColor = array_pad([], self::GRAPH_HEIGHT, '');
		for($i = 0; $i < self::GRAPH_WIDTH; ++$i){
			$elementValue = $this->get($i) ?? 0;
			$elementHeight = ceil($height / $elementValue * self::GRAPH_HEIGHT);
			for($ei = 0; $ei < self::GRAPH_HEIGHT; ++$ei){
				$color = ($elementHeight <= $ei) ? $this->color : '§0';
				if($lastestColor[$ei] === $color){
					$lines[$ei] .= $this->style;
				}else{
					$lines[$ei] .= $color . $this->style;
					$lastestColor[$ei] = $color;
				}
			}
		}
		return implode('\n', $lines);
	}
}