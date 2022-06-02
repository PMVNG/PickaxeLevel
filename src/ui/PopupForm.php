<?php

namespace DavidGlitch04\PMVNGPickaxe\ui;

use DavidGlitch04\PMVNGPickaxe\Pickaxe;
use pocketmine\player\Player;
use Vecnavium\FormsUI\CustomForm;
use Vecnavium\FormsUI\SimpleForm;

class PopupForm{

    protected Pickaxe $pickaxe;

    public function __construct(Player $player)
    {
        $this->pickaxe = Pickaxe::getInstance();
        $this->openForm($player);
    }

    private function openForm(Player $player): void{
        $form = new CustomForm(function (Player $player, array|null $data) {
            if (!isset($data)) {
                new MainForm($player);
            }
            if ($data[0] == true) {
                $current = $this->pic->get($player->getName())["Exp"];
                $currentlv = $this->pic->get($player->getName())["Level"];
                $currentne = $this->pic->get($player->getName())["NextExp"];
                $this->pic->set(($player->getName()), [
                    "Exp" => $current,
                    "Level" => $currentlv,
                    "NextExp" => $currentne,
                    "Popup" => true
                ]);
                $this->pic->save();
            }
            if ($data[0] == false) {
                $current = $this->pic->get($player->getName())["Exp"];
                $currentlv = $this->pic->get($player->getName())["Level"];
                $currentne = $this->pic->get($player->getName())["NextExp"];
                $this->pic->set(($player->getName()), [
                    "Exp" => $current,
                    "Level" => $currentlv,
                    "NextExp" => $currentne,
                    "Popup" => false
                ]);
                $this->pic->save();
            }
        });
        $form->setTitle("§6§lPoppup Pickaxe");
        $form->addToggle("§1§l↣ §aKéo sang phải để bật", false);
        $player->sendForm($form);
        return;
    }
}