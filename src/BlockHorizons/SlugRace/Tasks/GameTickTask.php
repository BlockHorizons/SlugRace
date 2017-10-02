<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Tasks;

use BlockHorizons\SlugRace\SluggishLoader;
use pocketmine\scheduler\PluginTask;

class GameTickTask extends PluginTask{

        /** @var SluggishLoader */
        private $loader = null;

        /**
         *
         * GameTickTask constructor.
         *
         * @param SluggishLoader $loader
         *
         */
        public function __construct(SluggishLoader $loader){
                $this->loader = $loader;
                parent::__construct($loader);
        }

        /**
         *
         * @param int $currentTick
         *
         */
        public function onRun(int $currentTick) : void{
                $this->loader->getGameManager()->tickArenas();
        }
}