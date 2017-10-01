<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Listeners;

use BlockHorizons\SlugRace\Model\PlayerSnailConverter;
use BlockHorizons\SlugRace\SluggishLoader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class PlayerListener implements Listener{

        /** @var SluggishLoader */
        private $loader = null;

        public function __construct(SluggishLoader $loader){
                $this->loader = $loader;
        }

        /**
         * @return SluggishLoader
         */
        public function getLoader() : SluggishLoader{
                return $this->loader;
        }

        /**
         * TODO: Remove. This is for snail testing purposes only.
         *
         * @param PlayerJoinEvent $event
         */
        public function onJoin(PlayerJoinEvent $event) : void{
                $converter = new PlayerSnailConverter();
                $converter->convertToSnail($event->getPlayer());
        }
}