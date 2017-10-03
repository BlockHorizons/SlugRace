<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Game;

use BlockHorizons\SlugRace\Data\ArenaConfiguration;
use BlockHorizons\SlugRace\Exceptions\InvalidArenaStateException;
use BlockHorizons\SlugRace\Game\Team\Team;
use BlockHorizons\SlugRace\Lang\Translator;
use BlockHorizons\SlugRace\SluggishLoader;
use BlockHorizons\SlugRace\Snail;
use BlockHorizons\SlugRace\Utils\StringUtils;
use pocketmine\level\Position;

class Arena{

        private static $arenaCount = 0;

        const STATE_IDLE = 0;
        const STATE_AWAITING_PLAYERS = 1;
        const STATE_RUNNING = 2;

        /** @var SluggishLoader */
        private $loader = null;
        /** @var ArenaConfiguration */
        private $arenaConfiguration = null;
        /** @var string */
        public $arenaName = "";
        /** @var int */
        private $arenaId = 0;

        /** @var Team */
        private $snailTeam = null;
        /** @var Team */
        private $slugTeam = null;

        /** @var int */
        private $state = self::STATE_IDLE;
        /** @var Position */
        protected $positionHandler = null;
        /** @var int */
        private $counter = 0;

        public function __construct(SluggishLoader $loader){
                $this->loader = $loader;
                $this->arenaConfiguration = new ArenaConfiguration($this);
                $this->arenaId = self::$arenaCount++;
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
         *
         * @return SluggishLoader
         *
         */
        public function getLoader() : SluggishLoader{
                return $this->loader;
        }

        /**
         *
         * @return ArenaConfiguration
         *
         */
        public function getArenaConfiguration() : ArenaConfiguration{
                return $this->arenaConfiguration;
        }

        /**
         *
         * @return int
         *
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
         *
         * @param bool $literal
         *
         * @return bool
         *
         */
        public function isRunning(bool $literal = false) : bool{
                return ($literal ? $this->state === self::STATE_RUNNING : $this->state !== self::STATE_IDLE);
        }

        /**
         *
         * @return string
         *
         */
        public function getName() : string{
                return $this->arenaName;
        }

        /**
         *
         * @param string $arenaName
         *
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
         * @return Snail[]
         *
         */
        public function getMergedSnails() : array{
                return array_merge($this->getSnailTeam()->getSnails(), $this->getSlugTeam()->getSnails());
        }

        /**
         *
         * @param Snail $snail
         *
         * @return bool
         *
         */
        public function isPlaying(Snail $snail) : bool{
                return $this->getTeam($snail->getType())->snailInGame($snail);
        }

        /**
         *
         * @return Position
         *
         */
        public function getPositionHandler() : Position{
                return $this->positionHandler;
        }

        /**
         *
         * @param string $message
         *
         */
        public function broadcastMessage(string $message){
                $this->loader->getServer()->broadcastMessage($message, $this->getMergedSnails());
        }

        /**
         *
         * @param string $tip
         *
         */
        public function broadcastTip(string $tip){
                $this->loader->getServer()->broadcastTip($tip, $this->getMergedSnails());
        }

        /**
         *
         * @param string $popup
         *
         */
        public function broadcastPopup(string $popup){
                $this->loader->getServer()->broadcastPopup($popup, $this->getMergedSnails());
        }

        /**
         *
         * @param string $title
         * @param string $subtitle
         * @param int    $fadeIn
         * @param int    $stayIn
         * @param int    $fadeOut
         *
         */
        public function broadcastTitle(string $title, string $subtitle, int $fadeIn = 1, int $stayIn = 1, int $fadeOut = 1){
                $this->loader->getServer()->broadcastTitle($title, $subtitle, $fadeIn, $stayIn, $fadeOut, $this->getMergedSnails());
        }

        /**
         * @param Snail $snail
         *
         * @return void
         */
        public function addSnail(Snail $snail) : void{
                $team = $this->getTeam($snail->getType());
                switch($team->addSnail($snail)){
                        case Team::JOIN_FAIL_GAME_FULL:
                                $snail->getPlayer()->sendMessage(StringUtils::colorFormatter(Translator::getMessage('join.error-game-full')));
                                break;
                        case Team::JOIN_FAIL_ALREADY_PLAYING:
                                $snail->getPlayer()->sendMessage(StringUtils::colorFormatter(Translator::getMessage('join.error-already-playing')));
                                break;
                        case Team::JOIN_FAIL_INCOMPATIBLE_SNAIL:
                                $snail->getPlayer()->sendMessage(StringUtils::colorFormatter(Translator::getMessage('join.error-incompatible-snail')));
                                break;
                        case Team::JOIN_SUCCESS:
                                $this->broadcastMessage(StringUtils::colorFormatter(Translator::getMessage('join.success', '', ['{player}' => $snail->getPlayer()->getName()])));
                                break;
                }
        }

        public function removeSnail(Snail $snail){
                if($this->isPlaying($snail)){
                        $this->getTeam($snail->getType())->removeSnail($snail);
                        $this->broadcastMessage(StringUtils::colorFormatter(Translator::getMessage('quit', '', ['{player}' => $snail->getPlayer()->getName()])));
                }
        }


        /**
         *
         * TODO: runtime logic and refresh signs
         *
         */
        public function doTick(){
			$this->counter++;
        }
}