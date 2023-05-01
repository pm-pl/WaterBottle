<?php

namespace WaterBottle;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }    

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();

        if($item->getId() === ItemIds::GLASS_BOTTLE && $block->getId() === ItemIds::WATER) {
            if($player->getInventory()->canAddItem(ItemFactory::getInstance()->get(ItemIds::POTION, 0, 1))) {
                $item = ItemFactory::getInstance()->get(ItemIds::POTION, 0, 1);
                $item->setCustomName("§r§bSu Şişesi");
                $player->getInventory()->addItem($item);
                $player->sendMessage("§9Bir su şişesi doldurdun!");
                
                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(ItemIds::GLASS_BOTTLE, 0, 1));
            } else {
                $player->sendMessage("§cEyvah, Envanterin dolu, su şişesi dolduramazsın!");
            }
        }        

    }
}
