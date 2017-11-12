<?php

namespace solo\smonitor\monitor;

use pocketmine\Server;

class NetworkMonitor extends Monitor{

	private $latestDownload;

	public function getName(){
		return "Network";
	}

	public function getColor(){
		return '§6';
	}

	public function update(){
		$this->push(round(Server::getInstance()->getNetwork()->getUpload() / 1024, 2));
    $this->latestDownload = round(Server::getInstance()->getNetwork()->getDownload() / 1024, 2);
	}

	public function createGraph(){
		return
			"§7[" . $this->getName() . "]   "
			. $this->getColor() . "§lUpload§r§f: " . $this->getLatest() . "KB/s   "
			. $this->getColor() . "§lDownload§r§f: " . $this->latestDownload . "KB/s\n"
			. parent::createGraph();
	}
}
