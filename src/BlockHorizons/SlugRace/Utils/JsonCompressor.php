<?php

namespace BlockHorizons\SlugRace\Utils;

class JsonCompressor{

        /**
         *
         * @param array $data
         *
         * @return string
         *
         */
        public static function compress(array $data) : string{
                return gzencode(json_encode($data));
        }

        /**
         *
         * @param string $data
         *
         * @return array
         *
         */
        public static function decompress(string $data) : array{
                return json_decode(gzdecode($data), true);
        }

}