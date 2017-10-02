<?php

declare(strict_types = 1);

namespace BlockHorizons\SlugRace\Tasks;

use BlockHorizons\SlugRace\Model\PlayerSnailConverter;
use pocketmine\entity\Skin;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class SnailConverterTask extends AsyncTask{

        /** @var string */
        private $playerName = "";
        /** @var string */
        private $data = "";
        /** @var string */
        private $geometry = "";
        /** @var string */
        private $capeData = "";

        public function __construct(string $playerName, string $skinData, string $newGeometry, string $capeData){
                $this->playerName = $playerName;
                $this->data = $skinData;
                $this->geometry = $newGeometry;
                $this->capeData = $capeData;
        }

        public function onRun() : void{
                $converter = new PlayerSnailConverter();
                $skin = $converter->reconstructSkin($this->data);

		        foreach($skin->getAllMinimumIndexes() as $index){
				        $skin->setRGB($index, random_int(170, 190), random_int(120, 140), random_int(70, 85));
		        }
		        foreach($skin->getHeadIndexes() as $index){
				        $skin->setRGB($index, random_int(50, 75), random_int(200, 230), random_int(50, 65));
		        }

                $this->setResult($skin->getRawData());
        }

        public function onCompletion(Server $server) : void{
                $player = $server->getPlayerExact($this->playerName);
                if($player === null){
                        return;
                }
                $skinData = $this->getResult();
                $newSkin = new Skin("snail_skin", $skinData, $this->capeData, "geometry.snail", $this->geometry);
                $player->setSkin($newSkin);
                $player->sendSkin($player->getServer()->getOnlinePlayers());
        }
}
