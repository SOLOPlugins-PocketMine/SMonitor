<?php

namespace solo\smonitor;

use pocketmine\Player;
use solo\smonitor\monitor\Monitor;

class Session{

	private $player;

	private $monitor;

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function getPlayer(){
		return $this->player;
	}

	public function setMonitor(Monitor $monitor){
		$this->monitor = $monitor;
	}

	public function update(){
		if($this->monitor === null){
			return;
		}
		$this->player->addActionBarMessage($this->monitor->createGraph());
	}
}