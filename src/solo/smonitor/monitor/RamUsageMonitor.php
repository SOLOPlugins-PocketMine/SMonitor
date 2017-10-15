<?php

namespace solo\smonitor\monitor;

use pocketmine\utils\Utils;

class RamUsageMonitor extends Monitor{

	private $maximumMemory = 100;

	public function getName(){
		return "Ram Usage";
	}

	public function getColor(){
		return '§5';
	}

	public function update(){
		$rUsage = Utils::getRealMemoryUsage();
		$mUsage = Utils::getMemoryUsage(true);

		$this->push(round(($mUsage[1] / 1024) / 1024, 2));
		$this->maximumMemory = round(($mUsage[2] / 1024) / 1024, 2);
	}

	public function getGraphMaxHeight(){
		return $this->maximumMemory;
	}

	public function createGraph(){
		return
			"§7[" . $this->getName() . "]   "
			. $this->getColor() . "§lRam Usage§r§f: " . $this->getLatest() . "/" . $this->maximumMemory . " MB\n"
			. parent::createGraph();
	}
}