<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace;

use pocketmine\Player;

class Snail{

		const TYPE_SNAIL = 0;
		const TYPE_SLUG = 1;

		/** @var Player */
		private $player = null;
		/** @var int */
		private $type = 0;

		public function __construct(Player $player, int $type = self::TYPE_SNAIL){
				$this->player = $player;
				$this->type = $type;
		}

		/**
		 * @return Player
		 */
		public function getPlayer() : Player{
				return $this->player;
		}

		/**
		 * @return int
		 */
		public function getType() : int{
				return $this->type;
		}

		/**
		 * @param int $type
		 */
		public function setType(int $type) : void{
				$this->type = $type;
		}

		/**
		 * @return bool
		 */
		public function checkValid() : bool{
				return $this->player->isOnline();
		}
}