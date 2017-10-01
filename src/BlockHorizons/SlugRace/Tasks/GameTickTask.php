<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Tasks;

use BlockHorizons\SlugRace\SluggishLoader;
use pocketmine\scheduler\PluginTask;

class GameTickTask extends PluginTask{

        /**
         *
         * GameTickTask constructor.
         *
         * @param SluggishLoader $owner
         *
         */
        public function __construct(SluggishLoader $owner){
                parent::__construct($owner);
        }

        /**
         *
         * TODO: tick all loaded games
         *
         * @param int $currentTick
         *
         */
        public function onRun(int $currentTick){

        }
}