<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace;

use BlockHorizons\SlugRace\Listeners\PlayerListener;
use BlockHorizons\SlugRace\Tasks\GameTickTask;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;

class SluggishLoader extends PluginBase{

        public function onEnable(){
                $this->registerCommands();
                $this->registerListeners();
                //$this->scheduleTasks();
        }

        public function onDisable(){

        }

        public function registerCommands() : void{
                $this->getServer()->getCommandMap()->registerAll("slugrace", [

                ]);
        }

        public function registerListeners() : void{
                $listeners = [new PlayerListener($this)];
                foreach($listeners as $listener){
                        $this->getServer()->getPluginManager()->registerEvents($listener, $this);
                }
        }

        public function scheduleTasks() : void{
                $delayed = [];
                $repeating = [20 => new GameTickTask($this)];

                foreach($delayed as $ticks => &$delayedTask){
                        if($delayedTask instanceof PluginTask){
                                $handler = $this->getServer()->getScheduler()->scheduleRepeatingTask($delayedTask, $ticks);
                                $delayedTask->setHandler($handler);
                        }
                }

                foreach($repeating as $ticks => &$repeatingTask){
                        if($repeatingTask instanceof PluginTask){
                                $handler = $this->getServer()->getScheduler()->scheduleRepeatingTask($repeatingTask, $ticks);
                                $repeatingTask->setHandler($handler);
                        }
                }
        }
}