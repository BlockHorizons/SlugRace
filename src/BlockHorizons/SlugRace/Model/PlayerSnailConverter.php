<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Model;

use BlockHorizons\SlugRace\Tasks\SnailConverterTask;
use pocketmine\Player;

class PlayerSnailConverter {

	/**
	 * Converts the player in this object to a snail.
	 */
	public function convertToSnail(Player $player): void {
		$oldSkin = $player->getSkin();
		$player->getServer()->getScheduler()->scheduleAsyncTask(new SnailConverterTask($player->getName(), $oldSkin->getSkinData(), file_get_contents(__DIR__ . '\snail_model.json'), $oldSkin->getCapeData()));
	}

	public function convertToSlug(): void {

	}

	/**
	 * Reconstructs a player skin into a pixel representation.
	 * This function is time extensive and should be used asynchronously.
	 *
	 * @param string $skinData
	 *
	 * @return PlayerSkin
	 */
	public function reconstructSkin(string $skinData): PlayerSkin {
		return new PlayerSkin($skinData);
	}
}