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

                foreach($skin->getHeadIndexes() as $index){
                        $skin->setRGB($index, random_int(50, 75), random_int(200, 230), random_int(50, 65));
                }
                foreach($skin->getBodyIndexes() as $index){
                        $skin->setRGB($index, random_int(230, 250), random_int(130, 150), random_int(55, 70));
                }

                $this->setResult($skin->getRawData());
        }

        public function onCompletion(Server $server) : void{
                $player = $server->getPlayer($this->playerName);
                if($player === null){
                        return;
                }
                $skinData = $this->getResult();
                $newSkin = new Skin("snail_skin", $skinData, $this->capeData, "snail_geometry_model", $this->geometry);
                $player->setSkin($newSkin);
                $player->sendSkin($player->getServer()->getOnlinePlayers());
        }
}