<?php
/*

TPS / RAM / LOAD / NETWORK / INFO / LEVEL
STATUS
======================================
업타임 : 30:23:12
TPS : 13.24(100%)   평균 TPS : 18.13(87.23%)
업 : 577.23KB/s   다운 : 320.21KB/s
램 사용량 : 1320MB / 8192MB



TPS / RAM / LOAD / NETWORK / INFO / LEVEL
STATUS
======================================
TPS : 20(12%)   평균 TPS : 20(7%)
20::||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
00::||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
00::||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
00::||||||||||||||||||||||||||||||||||||||||||||||||||||||||||
01::||||||||||||||||||||||||||||||||||||||||||||||||||||||||||



======================================
Uptime : 30:12.54   Thread Count : 5
Online Players : 23/100
TPS : 20(12%)    Average TPS : 20(3%)
RAM : 20MB / 320MB / 1200MB
Upload : 200KB/s   Download : 120KB/s



======================================
Motd : SOLO SERVER   
PM Version : 1.6   API : 2.0.0
IP : 0.0.0.0  PORT : 19132
Plugins : 32  Levels : 4

*/

namespace solo\smonitor;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\scheduler\PluginTask;

use solo\smonitor\monitor\Monitor;

class SMonitor extends PluginBase implements Listener{

  public static $prefix = "§b§l[SMonitor] §r§7";

  private $monitors = [];

  private $sessions = [];

  public function onEnable(){
    foreach([
      "TPSMonitor", "LoadMonitor", "RamUsageMonitor", "NetworkMonitor"
    ] as $class){
      $class = "\\solo\\smonitor\\monitor\\" . $class;
      $this->addMonitor(new $class());
    }

		$this->getServer()->getPluginManager()->registerEvents($this, $this);

    $this->getServer()->getScheduler()->scheduleRepeatingTask(new class($this) extends PluginTask{
      public function onRun(int $currentTick){
        foreach($this->owner->getMonitors() as $monitor){
          $monitor->update();
        }
        foreach($this->owner->getSessions() as $session){
          $session->update();
        }
      }
    }, 20);
  }

  public function getMonitors(){
    return $this->monitors;
  }

  public function getMonitor(string $name){
    $found = null;
    $name = strtolower($name);
    $delta = PHP_INT_MAX;
    foreach($this->getMonitors() as $monitor){
      if(stripos($monitor->getName(), $name) === 0){
        $curDelta = strlen($monitor->getName()) - strlen($name);
        if($curDelta < $delta){
          $found = $monitor;
          $delta = $curDelta;
        }
        if($curDelta === 0){
          break;
        }
      }
    }
    return $found;
  }

  public function addMonitor(Monitor $monitor){
    $this->monitors[strtolower($monitor->getName())] = $monitor;
  }

  public function getSessions(){
    return $this->sessions;
  }

  public function getSession(Player $player){
    return $this->sessions[$player->getId()] ?? null;
  }

  public function createSession(Player $player){
    return $this->sessions[$player->getId()] = new Session($player);
  }

  public function removeSession(Player $player){
    if(isset($this->sessions[$player->getId()])){
      unset($this->sessions[$player->getId()]);
      return true;
    }
    return false;
  }

  public function handlePlayerQuit(PlayerQuitEvent $event){
    $this->removeSession($event->getPlayer());
  }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
    if(!$sender instanceof Player){
      $sender->sendMessage(SMonitor::$prefix . "인게임에서만 사용 가능합니다.");
      return true;
    }
    switch($args[0] ?? "null"){
      case "off":
        if($this->removeSession($sender)){
          $sender->sendMessage(SMonitor::$prefix . "작업관리자를 껐습니다.");
        }else{
          $sender->sendMessage(SMonitor::$prefix . "작업관리자가 켜져있지 않습니다.");
        }
        break;

      default:
        $monitor = $this->getMonitor(implode(" ", $args));
        if($monitor === null){
          $sender->sendMessage(SMonitor::$prefix . "사용법 : /작업관리자 <" . implode("/", array_map(function($monitor){ return strtolower($monitor->getName()); }, $this->getMonitors())) . "/off> - 작업관리자의 모드를 변경합니다.");
          break;
        }
        $session = $this->getSession($sender) ?? $this->createSession($sender);
        $session->setMonitor($monitor);
        $sender->sendMessage(SMonitor::$prefix . "작업관리자 모드를 " . $monitor->getName() . " 으로 변경하였습니다.");
        break;
    }
    return true;
  }
}