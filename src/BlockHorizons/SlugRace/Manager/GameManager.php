<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Manager;

use BlockHorizons\SlugRace\Exceptions\InvalidArenaStateException;
use BlockHorizons\SlugRace\Game\Arena;

class GameManager implements Manager{

        /** @var Arena[] */
        protected $arenas = [];

        /**
         *
         * @return string
         *
         */
        public function getName() : string{
                return static::class;
        }

        /**
         *
         * @param Arena $arena
         *
         */
        public function loadArena(Arena $arena){
                $this->arenas[$arena->getId()] = $arena;
                $this->arenas[$arena->getName()] = $arena;
        }

        /**
         *
         * @param mixed $v
         *
         * @return bool
         *
         */
        public function arenaIsLoaded($v) : bool{
                return isset($this->arenas[$v]);
        }

        /**
         *
         * @param int $id
         *
         * @return Arena|null
         *
         */
        public function getArenaById(int $id){
                if($this->arenaIsLoaded($id)){
                        return $this->arenas[$id];
                }
                return null;
        }

        /**
         *
         * @param string $name
         *
         * @return Arena|null
         *
         */
        public function getArenaByName(string $name){
                if($this->arenaIsLoaded($name)){
                        return $this->arenas[$name];
                }
                return null;
        }

        /**
         *
         * @param Arena $arena
         *
         */
        public function unloadArena(Arena $arena){
                if($this->arenaIsLoaded($arena->getId())){
                        unset($this->arenas[$arena->getId()]);
                        unset($this->arenas[$arena->getName()]);
                }
        }

        /**
         *
         * @param int $state
         *
         * @return array
         *
         */
        public function getArenasWithState(int $state) : array{
                $arenas = [];

                if(($state > Arena::STATE_RUNNING) or ($state < Arena::STATE_IDLE)){
                        throw new InvalidArenaStateException("Arena state must be in the range of 1-3");
                }

                foreach($this->arenas as $arena){
                        if($arena->getState() == $state){
                                $arenas[] = $state;
                        }
                }

                return $arenas;
        }

        public function tickArenas(){
                foreach($this->arenas as $arena){
                        $arena->doTick();
                }
        }
}