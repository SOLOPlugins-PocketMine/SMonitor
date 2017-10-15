<?php

namespace solo\smonitor\monitor;

use pocketmine\Server;

class LoadMonitor extends Monitor{

	public function getName(){
		return "Load";
	}

	public function getColor(){
		return '§b';
	}

	public function update(){
    	$this->push(Server::getInstance()->getTickUsage());
	}

	public function getGraphMaxHeight(){
		return 100;
	}

	public function createGraph(){
		$server = Server::getInstance();
		return
			"§7[" . $this->getName() . "]   "
			. $this->getColor() . "§lTPS§r§f: " . $server->getTicksPerSecond() . "   "
			. $this->getColor() . "§lAverage TPS§r§f: " . $server->getTicksPerSecondAverage() . "   "
			. $this->getColor() . "§lLoad§r§f: " . $server->getTickUsage() . "%\n"
			. parent::createGraph();
	}
}