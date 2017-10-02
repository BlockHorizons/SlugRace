<?php

namespace BlockHorizons\SlugRace\Utils;

use pocketmine\utils\TextFormat;

final class StringUtils{

        /**
         *
         * @param array $data
         *
         * @return string
         *
         */
        public static function jsonCompress(array $data) : string{
                return gzencode(json_encode($data));
        }

        /**
         *
         * @param string $data
         *
         * @return array
         *
         */
        public static function jsonDecompress(string $data) : array{
                return json_decode(gzdecode($data), true);
        }

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