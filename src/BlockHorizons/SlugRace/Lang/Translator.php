<?php

namespace BlockHorizons\SlugRace\Lang;

use BlockHorizons\SlugRace\Exceptions\UnknownLangException;

class Translator{

        /** @var string[] */
        public static $messages = [];

        /**
         *
         * @param string $lang
         *
         */
        public static function selectLang(string $lang) : void{
                $path = (__DIR__ . DIRECTORY_SEPARATOR . 'List' . DIRECTORY_SEPARATOR);
                if(file_exists($file = ($path . $lang . '.yml'))){
                        self::$messages = yaml_parse_file($file);
                        return;
                }
                throw new UnknownLangException("Language file not found!");
        }

        /**
         *
         * @param string   $message
         * @param string   $default
         * @param string[] $replaceValues
         *
         * @return string
         *
         */
        public static function getMessage(string $message, string $default = '', array $replaceValues = []) : string{
                $output = null;
                $keys = explode('.', $message);
                if(!isset(self::$messages[$output = array_shift($keys)])) return $default;
                $output = self::$messages[$output];
                do{
                        $nextKey = array_shift($keys);
                        if(!isset($output[$nextKey]) and !is_array($output)) return $default;
                        $output = $output[$nextKey];

                }while(count($keys) > 0);
                $output = str_replace(array_keys($replaceValues), array_values($replaceValues), $output);

                return $output;
        }

}