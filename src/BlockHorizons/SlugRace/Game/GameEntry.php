<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Game;

use BlockHorizons\SlugRace\Exceptions\DoubleSnailAssignmentException;
use BlockHorizons\SlugRace\Exceptions\InvalidSnailException;
use BlockHorizons\SlugRace\Snail;

class GameEntry{

        /** @var Snail|null */
        private $snail = null;

        public function __construct(Snail $snail = null){
                if($snail !== null){
                        if(!$snail->checkValid()){
                                throw new InvalidSnailException("Player of given snail is not online.");
                        }
                }
                $this->snail = $snail;
        }

        /**
         * @return Snail|null
         */
        public function getSnail() : ?Snail{
                return $this->snail;
        }

        /**
         * @return bool
         */
        public function isAssigned() : bool{
                return $this->snail !== null;
        }

        /**
         * @param Snail $snail
         */
        public function assignSnail(Snail $snail) : void{
                if($this->snail !== null){
                        throw new DoubleSnailAssignmentException("Only one snail can be assigned to a game entry at once.");
                }
                $this->snail = $snail;
        }
}