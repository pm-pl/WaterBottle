<?php

namespace WaterBottle;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private $config;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }    

    public function onPlayerInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();

        if($item->getId() === ItemIds::GLASS_BOTTLE && $block->getId() === ItemIds::WATER) {
            if($player->getInventory()->canAddItem(ItemFactory::getInstance()->get(ItemIds::POTION, 0, 1))) {
                $item = ItemFactory::getInstance()->get(ItemIds::POTION, 0, 1);
                $item->setCustomName($this->config->getNested("items.potion_name"));
                $player->getInventory()->addItem($item);
                $player->sendMessage($this->config->getNested("messages.filled_bottle"));
                
                $pk = new LevelSoundEventPacket();
                $pk->sound = LevelSoundEventPacket::SOUND_WATER;
                $pk->position = $player->getPosition();
                $player->sendDataPacket($pk);

                $player->getInventory()->removeItem(ItemFactory::getInstance()->get(ItemIds::GLASS_BOTTLE, 0, 1));
            } else {
                $player->sendMessage($this->config->getNested("messages.full_inventory"));
            }
        }        

    }
}
