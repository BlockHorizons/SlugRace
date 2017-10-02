<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Manager;

use BlockHorizons\SlugRace\SluggishLoader;
use BlockHorizons\SlugRace\Utils\StringUtils;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;

class SignManager implements Manager{

        /** @var SluggishLoader */
        private $loader = null;
        /** @var string */
        private $signsFile = '';
        /** @var Sign[][] */
        protected $signs = [];
        /** @var int[] */
        private $signCache = [];

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
         * SignManager constructor.
         *
         * @param SluggishLoader $loader
         *
         * @param string $signsFile
         *
         */
        public function __construct(SluggishLoader $loader, string $signsFile){
                $this->loader = $loader;
                $this->signsFile = $signsFile;
                $this->parseSigns(StringUtils::jsonDecompress(file_get_contents($signsFile)));
        }

        /**
         *
         * @param array $data
         *
         */
        public function parseSigns(array $data){
                foreach($data as $arenaId => $datum){
                        foreach($datum as $signId => $coords){
                                $level = $this->loader->getServer()->getLevelByName(array_pop($coords));
                                $tile = $level->getTile(new Vector3(...$coords));
                                if($tile instanceof Sign){
                                        $this->addSign($arenaId, $tile);
                                }
                        }
                }
        }

        public function saveSignData(){
                $data = [];
                foreach($this->signs as $arenaId => $signs){
                        foreach($signs as $signId => $sign){
                                $data[$arenaId] = [$sign->x, $sign->y, $sign->z, $sign->level->getName()];
                        }
                }

                file_put_contents($this->signsFile, StringUtils::jsonCompress($data));
        }

        /**
         *
         * @param Sign $sign
         *
         * @return bool
         *
         */
        public function checkIsGameSign(Sign $sign) : bool{
                return isset($this->signCache[$sign->getId()]);
        }

        /**
         *
         * @param Sign $sign
         * @param int  $arenaId
         *
         * @return bool
         *
         */
        private function cacheSign(Sign $sign, int $arenaId) : bool{
                if(!$this->checkIsGameSign($sign)){
                        $this->signCache[$sign->getId()] = $arenaId;
                        return true;
                }
                return false;
        }

        /**
         *
         * @param Sign $sign
         *
         * @return bool
         *
         */
        private function discardSign(Sign $sign) : bool{
                if($this->checkIsGameSign($sign)){
                        unset($this->signCache[$sign->getId()]);
                        return true;
                }
                return false;
        }

        /**
         *
         * @param Sign $sign
         *
         * @return int|null
         *
         */
        public function getArenaBySign(Sign $sign){
                if($this->checkIsGameSign($sign)){
                        return $this->signCache[$sign->getId()];
                }
                return null;
        }

        /**
         *
         * @param int $arenaId
         *
         * @return bool
         *
         */
        public function arenaSignsLoaded(int $arenaId) : bool{
                return isset($this->signs[$arenaId]);
        }

        /**
         *
         * @param int $arenaId
         * @param Sign  $sign
         *
         */
        public function addSign(int $arenaId, Sign $sign){
                $this->signs[$arenaId][$sign->getId()] = $sign;
                $this->cacheSign($sign, $arenaId);
        }

        /**
         *
         * @param int  $arenaId
         * @param Sign $sign
         *
         * @return bool
         *
         */
        public function deleteSign(int $arenaId, Sign $sign) : bool{
                if($this->checkIsGameSign($sign)){
                        $this->discardSign($sign);
                        unset($this->signs[$arenaId][$sign->getId()]);
                        return true;
                }
                return false;
        }

        /**
         *
         * @param int $arenaId
         *
         * @return Sign[]|array
         *
         */
        public function getGameSigns(int $arenaId) : array{
                if($this->arenaSignsLoaded($arenaId)){
                        return array_values($this->signs[$arenaId]);
                }
                return [];
        }

        /**
         *
         * TODO: implement efficient way of refreshing signs
         *
         * @param int $arenaId
         *
         * @return bool
         *
         */
        public function refreshSigns(int $arenaId) : bool{
                return false;
        }
}