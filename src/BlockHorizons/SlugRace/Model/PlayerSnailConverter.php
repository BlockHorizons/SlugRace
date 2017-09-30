<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Model;

use pocketmine\entity\Skin;
use pocketmine\Player;

class PlayerSnailConverter {

	/** @var Player */
	private $player = null;

	public function __construct(Player $player) {
		$this->player = $player;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	/**
	 * Converts the player in this object to a snail.
	 */
	public function convertToSnail(): void {
		$oldSkin = $this->player->getSkin();
		$newSkin = new Skin($oldSkin->getSkinId(), $oldSkin->getSkinData(), $oldSkin->getCapeData(), 'snail_geometry_model', file_get_contents(__DIR__ . '\snail_model.json'));
		$this->player->setSkin($newSkin);
		$this->player->sendSkin($this->player->getServer()->getOnlinePlayers());
	}

	public function convertToSlug(): void {

	}
}