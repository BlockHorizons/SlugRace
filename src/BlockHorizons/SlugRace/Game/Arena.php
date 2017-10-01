<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Game;

use BlockHorizons\SlugRace\Data\ArenaConfiguration;
use BlockHorizons\SlugRace\Exceptions\InvalidArenaStateException;
use BlockHorizons\SlugRace\Game\Team\Team;
use BlockHorizons\SlugRace\SluggishLoader;
use BlockHorizons\SlugRace\Snail;

class Arena{

        const STATE_IDLE = 0;
        const STATE_AWAITING_PLAYERS = 1;
        const STATE_RUNNING = 2;

        /** @var SluggishLoader */
        private $loader = null;
        /** @var ArenaConfiguration */
        private $arenaConfiguration = null;
        /** @var string */
        private $arenaName = "";
        /** @var int */
        private $arenaId = 0;

        /** @var Team */
        private $snailTeam = null;
        /** @var Team */
        private $slugTeam = null;

        /** @var int */
        private $state = self::STATE_IDLE;

        public function __construct(SluggishLoader $loader){
                $this->loader = $loader;
                $this->arenaConfiguration = new ArenaConfiguration($this);
                $this->arenaId = (time() + mt_rand(100, 999));
                $gameConf = $this->getArenaConfiguration()->getSetting('game', 8);
                if(is_array($gameConf)){
                        $this->snailTeam = new Team($gameConf['count']['snail']['max'], Snail::TYPE_SNAIL, []);
                        $this->slugTeam = new Team($gameConf['count']['slug']['max'], Snail::TYPE_SLUG, []);
                }else{
                        $this->snailTeam = new Team($gameConf, Snail::TYPE_SNAIL, []);
                        $this->slugTeam = new Team($gameConf, Snail::TYPE_SLUG, []);
                }
        }

        /**
         * @return SluggishLoader
         */
        public function getLoader() : SluggishLoader{
                return $this->loader;
        }

        /**
         * @return ArenaConfiguration
         */
        public function getArenaConfiguration() : ArenaConfiguration{
                return $this->arenaConfiguration;
        }

        /**
         * @return int
         */
        public function getState() : int{
                return $this->state;
        }

        /**
         *
         * @param int $state
         *
         */
        public function setState(int $state) : void{
                if(($state > self::STATE_RUNNING) or ($state < self::STATE_IDLE)){
                        throw new InvalidArenaStateException("Arena state must be in the range of 1-3");
                }
                $this->state = $state;
        }

        /**
         * @param bool $literal
         *
         * @return bool
         */
        public function isRunning(bool $literal = false) : bool{
                return ($literal ? $this->state === self::STATE_RUNNING : $this->state !== self::STATE_IDLE);
        }

        /**
         * @return string
         */
        public function getName() : string{
                return $this->arenaName;
        }

        /**
         * @param string $arenaName
         */
        public function setName(string $arenaName) : void{
                $this->arenaName = $arenaName;
        }

        /**
         *
         * @return int
         *
         */
        public function getId() : int{
                return $this->arenaId;
        }

        /**
         *
         * @return Team
         *
         */
        public function getSnailTeam() : Team{
                return $this->snailTeam;
        }

        /**
         *
         * @return Team
         *
         */
        public function getSlugTeam() : Team{
                return $this->slugTeam;
        }

        /**
         *
         * @param int $snailType
         *
         * @return Team
         *
         */
        public function getTeam(int $snailType) : Team{
                switch($snailType){
                        case 0:
                                return $this->snailTeam;
                        case 1:
                                return $this->slugTeam;
                }
                return $this->snailTeam;
        }

        /**
         *
         * @param GameEntry $entry
         *
         */
        public function handleGameEntry(GameEntry $entry) : void{
                $snail = $entry->getSnail();
                $team = $this->getTeam($snail->getType());

                switch($team->addSnail($snail)){
                        case Team::JOIN_FAIL_GAME_FULL:
                                //TODO: send game full message
                                break;
                        case Team::JOIN_FAIL_ALREADY_PLAYING:
                                //TODO: send already playing message
                                break;
                        case Team::JOIN_FAIL_INCOMPATIBLE_SNAIL:
                                //TODO: send incompatible snail message
                                break;
                        case Team::JOIN_SUCCESS:
                                //TODO: send join success message
                                break;
                }
        }

        /**
         *
         * TODO: runtime logic and refresh signs
         *
         */
        public function doTick(){
        }
}