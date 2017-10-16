<?php

namespace solo\smonitor\monitor;

use pocketmine\Server;

class TPSMonitor extends Monitor{

	public function getName(){
		return "TPS";
	}

	public function getColor(){
		return '§b';
	}

	public function update(){
    		$this->push(Server::getInstance()->getTicksPerSecondAverage());
	}

	public function getGraphMaxHeight(){
		return 20;
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
