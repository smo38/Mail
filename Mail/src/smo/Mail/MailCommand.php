<?php

declare(strict_types=1);

namespace smo\Mail;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use smo\Mail\form\MailMainForm;

class MailCommand extends Command{

    public function __construct(){

        parent::__construct("mail", "Mailコマンド", "/mail");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){

        if(!$sender instanceof Player){
            $sender->sendMessage("§c[Mail] >> ゲーム内で実行してください");
            return;
        }

        $sender->sendForm(new MailMainForm());
    }
}