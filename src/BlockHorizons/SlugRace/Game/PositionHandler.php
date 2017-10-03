<?php

namespace BlockHorizons\SlugRace\Game;

use BlockHorizons\SlugRace\Exceptions\InvalidSnailException;
use BlockHorizons\SlugRace\Game\Team\Team;
use BlockHorizons\SlugRace\SluggishLoader;
use BlockHorizons\SlugRace\Snail;
use pocketmine\level\Position;

class PositionHandler{

        /** @var Position[][] */
        protected $startPositions = [];
        /** @var int[][] */
        protected $usedPositions = [];
        /** @var Position */
        protected $lobbyPosition = null;

        /** @var SluggishLoader */
        private $loader = null;

        /**
         *
         * PositionHandler constructor.
         *
         * @param SluggishLoader $loader
         *
         */
        public function __construct(SluggishLoader $loader){
                $this->loader = $loader;
        }

        /**
         *
         * @param array $position
         *
         * @return null|Position
         *
         */
        public function parsePosition(array $position){
                $position[3] = $level = $this->loader->getServer()->getLevelByName($position[3]);
                if($level !== null){
                        return new Position(...$position);
                }
                return null;
        }

        /**
         *
         * @param Team     $team
         * @param Position $position
         *
         */
        public function addTeamStartPosition(Team $team, Position $position){
                $this->startPositions[$team->getSnailType()][] = $position;
                $this->usedPositions[$team->getSnailType()] = count($this->startPositions[$team->getSnailType()]);
        }

        /**
         *
         * @param Team  $team
         * @param array $positions
         *
         */
        public function setTeamStartPositions(Team $team, array $positions){
                $this->startPositions[$team->getSnailType()] = [];
                foreach($positions as $position){
                        if($position instanceof Position){
                                $this->startPositions[$team->getSnailType()][] = $position;
                        }else{
                                $this->startPositions[$team->getSnailType()][] = $this->parsePosition($position);
                        }
                }
                $this->usedPositions[$team->getSnailType()] = count($this->startPositions[$team->getSnailType()]);
        }

        /**
         *
         * @param Team $team
         *
         * @return bool
         *
         */
        public function isStartPositionAvailable(Team $team) : bool{
                return ($this->getAvailableStartPositionCount($team) != 0);
        }

        /**
         *
         * @param Team $team
         *
         * @return int
         *
         */
        public function getAvailableStartPositionCount(Team $team) : int{
                return count($this->startPositions[$team->getSnailType()]);
        }

        /**
         *
         * @param Team $team
         *
         */
        public function getAvailableStartPosition(Team $team){
                //TODO: return an available position if there is one
        }

        /**
         *
         * @param Team $team
         *
         * @return Position[]
         *
         */
        public function getTeamStartPositions(Team $team) : array{
                return $this->startPositions[$team->getSnailType()];
        }

        /**
         *
         * @return Position[]
         *
         */
        public function getAllStartPositions() : array{
                return $this->startPositions;
        }

        /**
         *
         * @param Team $team
         *
         * @param Snail $snail
         *
         */
        public function addToStartPosition(Team $team, Snail $snail){
                //TODO: add snail to start of race track
        }

        /**
         *
         * @param Position $position
         *
         */
        public function setLobbyPosition(Position $position){
                $this->lobbyPosition = $position;
        }

        /**
         *
         * @return Position
         *
         */
        public function getLobbyPosition() : Position{
                return $this->lobbyPosition;
        }

        /**
         *
         * @param Snail $snail
         *
         * @return bool
         *
         */
        public function teleportToLobby(Snail $snail) : bool{
                if(!$snail->checkValid()){
                        throw new InvalidSnailException("Player of given snail is not online.");
                }
                $snail->getPlayer()->teleport($this->lobbyPosition);
                return true;
        }

}