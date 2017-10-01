<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Data;

use BlockHorizons\SlugRace\Game\Arena;

class ArenaConfiguration implements \JsonSerializable{

        /** @var Arena */
        private $arena = null;
        /** @var array */
        private $defaultSettings = [];
        /** @var array */
        private $arenaSettings = [];

        public function __construct(Arena $arena){
                $this->arena = $arena;
                if(!file_exists($path = $arena->getLoader()->getDataFolder() . "arenas/" . $this->arena->getName())){
                        copy(__DIR__ . "defaultArena.json", $path);
                }
                $this->defaultSettings = json_decode(file_get_contents(__DIR__ . "defaultArena.json"));
                $this->arenaSettings = json_decode(file_get_contents($path));
        }

        /**
         * @return Arena
         */
        public function getArena() : Arena{
                return $this->arena;
        }

        /**
         *
         * @param string $index
         *
         * @return bool
         *
         */
        public function settingExists(string $index) : bool{
                return isset($this->arenaSettings[$index]);
        }

        /**
         *
         * @param string $setting
         * @param mixed  $default
         *
         * @return mixed
         *
         */
        public function getSetting(string $setting, $default = false){
                if($this->settingExists($setting)){
                        return $this->arenaSettings[$setting];
                }
                return $default;
        }

        /**
         *
         * @param string $setting
         * @param mixed  $value
         *
         * @return bool
         *
         */
        public function setSetting(string $setting, $value) : bool{
                if($this->settingExists($setting) and !is_object($value)){
                        $this->arenaSettings[$setting] = $value;
                        return true;
                }
                return false;
        }

        /**
         *
         * @param string $setting
         *
         * @return bool
         *
         */
        public function resetSettingToDefault(string $setting) : bool{
                if(isset($this->defaultSettings[$setting]) and $this->settingExists($setting)){
                        $this->arenaSettings[$setting] = $this->defaultSettings[$setting];
                        return true;
                }
                return false;
        }

        public function resetAllSettingsToDefault() : void{
                $this->arenaSettings = $this->defaultSettings;
        }

        public function jsonSerialize() : array{
                return ["name" => $this->arena->getName(), "config" => $this->arenaSettings];
        }
}