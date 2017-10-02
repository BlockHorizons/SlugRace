<?php

namespace BlockHorizons\SlugRace\Game\Team;
use BlockHorizons\SlugRace\Exceptions\InvalidSnailException;
use BlockHorizons\SlugRace\Snail;
class Team{

        const JOIN_FAIL_GAME_FULL = 0;
        const JOIN_FAIL_ALREADY_PLAYING = 1;
        const JOIN_FAIL_INCOMPATIBLE_SNAIL = 2;
        const JOIN_SUCCESS = 4;

        /** @var int */
        private $snailType = 0;
        /** @var Snail[] */
        public $snails = [];
        /** @var int */
        private $maxSize = 0;

        /**
         *
         * Team constructor.
         *
         * @param int   $maxSize
         * @param int   $type
         * @param array $defaultSnails
         *
         */
        public function __construct(int $maxSize, int $type, array $defaultSnails = []) {
		        $this->maxSize = $maxSize;
		        $this->snailType = $type;
		        $this->snails = $defaultSnails;
        }

		/**
		 * @return int
		 */
        public function getMaxSize() : int{
        		return $this->maxSize;
        }

        /**
         *
         * @param Snail $snail
         *
         * @return bool
         *
         */
        public function snailInGame(Snail $snail) : bool{
                $id = $snail->getPlayer()->getUniqueId()->toString();
                return isset($this->snails[$id]);
        }

        /**
         *
         * @return array
         *
         */
        public function getSnails() : array{
                return $this->snails;
        }

        /**
         *
         * @return int
         *
         */
        public function getSnailCount() : int{
                return count($this->snails);
        }

        /**
         *
         * @param Snail $snail
         *
         * @return bool
         *
         */
        public function addSnail(Snail $snail) : bool{
                $id = $snail->getPlayer()->getUniqueId()->toString();
                if(!$this->snailInGame($snail)){
                        if(!$snail->checkValid()){
                                throw new InvalidSnailException("Player of given snail is not online.");
                        }
                        if($this->getSnailCount() >= $this->maxSize) return self::JOIN_FAIL_GAME_FULL;
                        if($this->snailType != $snail->getType()) return self::JOIN_FAIL_INCOMPATIBLE_SNAIL;
                        $this->snails[$id] = $snail;
                        return self::JOIN_SUCCESS;
                }
                return self::JOIN_FAIL_GAME_FULL;
        }

        /**
         *
         * @param Snail $snail
         *
         * @return bool
         *
         */
        public function removeSnail(Snail $snail) : bool{
                $id = $snail->getPlayer()->getUniqueId()->toString();
                if($this->snailInGame($snail)){
                        unset($this->snails[$id]);
                        return true;
                }
                return false;
        }

}