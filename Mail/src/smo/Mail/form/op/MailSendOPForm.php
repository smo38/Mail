<?php

declare(strict_types=1);

namespace smo\Mail\form\op;

use pocketmine\form\Form;
use pocketmine\Player;
use smo\Mail\System;

class MailSendOPForm implements Form{

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        if(!isset($data[0]) or !isset($data[1])){
            $player->sendMessage("§c[MailAdmin] >> 内容が不十分です");
            return;
        }

        System::get()->sendAll($data[0], $data[1]);
        $player->sendMessage("§a[MailAdmin] >> 一斉送信に成功しました");
    }

    public function jsonSerialize(){

        return [

            "type" => "custom_form",
            "title" => "§cMailAdmin",
            "content" => [
                [
                    "type" => "input",
                    "text" => "[件名]",
                    "placeholder" => "ここに記入",
                    "default" => ""
                ],
                [
                    "type" => "input",
                    "text" => "[内容] # を挟むと改行できます",
                    "placeholder" => "ここに記入",
                    "default" => ""
                ]
            ]
        ];
    }
}
