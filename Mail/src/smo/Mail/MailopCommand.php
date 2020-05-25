<?php

declare(strict_types=1);

namespace smo\Mail;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use smo\Mail\form\op\MailMainOPForm;

class MailopCommand extends Command{

    public function __construct(){

        parent::__construct("mailop", "Mailコマンド (OP用)", "/mailop");
        $this->setPermission("mail.op");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){

        if(!$this->testPermission($sender)) return false;

        if(!$sender instanceof Player){
            $sender->sendMessage("§c[Mail] >> ゲーム内で実行してください");
            return;
        }

        $sender->sendForm(new MailMainOPForm());
    }
}