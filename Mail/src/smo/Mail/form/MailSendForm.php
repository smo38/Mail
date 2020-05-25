<?php

declare(strict_types=1);

namespace smo\Mail\form;

use pocketmine\form\Form;
use pocketmine\Player;
use smo\Mail\System;

class MailSendForm implements Form{



    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        if(!isset($data[0]) or !isset($data[1]) or !isset($data[2])){
            $player->sendMessage("§c[Mail] >> 内容が不十分です");
            return;
        }

        if(!System::get()->existsPlayer($data[0])){
            $player->sendMessage("§c[Mail]");
        }

        System::get()->sendMail($player, $data[0], $data[1], $data[2]);
        $player->sendMessage("§a[Mail] >> 送信に成功しました");
    }

    public function jsonSerialize(){

        return [

            "type" => "custom_form",
            "title" => "Mail",
            "content" => [
                [
                    "type" => "input",
                    "text" => "[送り先] Admin と入力すると運営宛に送れます",
                    "placeholder" => "ここに記入",
                    "default" => ""
                ],
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
