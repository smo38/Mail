<?php

declare(strict_types=1);

namespace smo\Mail;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener{

	public function onEnable(): void {

        (new System($this));
	    $this->getLogger()->notice("Mail を読み込みました");
	    $this->getServer()->getPluginManager()->registerEvents($this, $this);
	    $this->getServer()->getCommandMap()->register("mail", new MailCommand());
	    $this->getServer()->getCommandMap()->register("mailop", new MailopCommand());
	}

	public function onJoin(PlayerJoinEvent $event): void {

	    $player = $event->getPlayer();
	    $name = $player->getName();
	    $system = System::get();

	    if(!$system->existsPlayer($name)){
	        echo "register\n";
	        $system->registerPlayer($name);
        }else{
	        $player->sendMessage("§a[Mail] >> ".count($system->getPlayerMail($name))."件のメールがあります");
        }

	    if($player->isOp()) $player->sendMessage("§a[MailAdmin] >> 運営あてに".count($system->getPlayerMail("Admin"))."件のメールがあります");
    }

	public function onDisable(): void {

		System::get()->saveConfig();
	}
}
