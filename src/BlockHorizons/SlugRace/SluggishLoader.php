<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace;

use BlockHorizons\SlugRace\Lang\Translator;
use BlockHorizons\SlugRace\Listeners\PlayerListener;
use BlockHorizons\SlugRace\Manager\GameManager;
use BlockHorizons\SlugRace\Manager\SignManager;
use BlockHorizons\SlugRace\Tasks\GameTickTask;
use BlockHorizons\SlugRace\Utils\JsonCompressor;
use BlockHorizons\SlugRace\Utils\StringUtils;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\PluginTask;

class SluggishLoader extends PluginBase{

        const AUTHORS = ['Sandertv', 'xBeastMode', 'BlockHorizons'];

        /** @var GameManager */
        private $gameManager = null;
        /** @var SignManager */
        private $signManager = null;

        public function onEnable(){
                $this->registerCommands();
                $this->registerListeners();
                //$this->scheduleTasks();

                $this->saveResource('config.yml');
                if(!file_exists($this->getDataFolder() . 'arenas/')){
                        mkdir($this->getDataFolder() . 'arenas/');
                }
                if(!file_exists($this->getDataFolder() . 'signs.dat')){
                        file_put_contents($this->getDataFolder() . 'signs.dat', JsonCompressor::compress([]));
                }
                if(!file_exists($this->getDataFolder() . 'lang/')){
                        mkdir($this->getDataFolder() . 'lang/');
                }

                $this->gameManager = new GameManager();
                $this->signManager = new SignManager($this, $this->getDataFolder() . 'signs.dat');

                Translator::setLanguagePath($this->getDataFolder().'lang/');
                Translator::selectLang(((string)$this->getConfig()->get('language')));

                $this->getLogger()->info(StringUtils::formatter(StringUtils::colorFormatter("&aPlugin by &e%1 &aand &e%2 &a@ &6%3"), ...self::AUTHORS));
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

        /**
         *
         * @return GameManager
         *
         */
        public function getGameManager() : GameManager{
                return $this->gameManager;
        }

        /**
         *
         * @return SignManager
         *
         */
        public function getSignManager() : SignManager{
                return $this->signManager;
        }
}