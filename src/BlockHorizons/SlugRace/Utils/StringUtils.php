<?php

namespace BlockHorizons\SlugRace\Utils;

use pocketmine\utils\TextFormat;

final class StringUtils{

        /**
         *
         * @param string $string
         * @param array  $params
         *
         * @return string
         *
         */
        public static function formatter(string $string, ...$params) : string{
                foreach($params as $key => &$val){
                        $string = str_replace(("%" . ($key + 1)), $val, $string);
                }
                return $string;
        }

        /**
         *
         * WARNING: this may not work in future Minecraft updates
         *
         * @param string $message
         * @param string $replacerSymbol
         *
         * @return string
         *
         */
        public static function colorFormatter(string $message, string $replacerSymbol = "&") : string{
                return str_replace($replacerSymbol, TextFormat::ESCAPE, $message);
        }

}