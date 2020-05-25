<?php

declare(strict_types=1);

namespace smo\Mail\form\op;

use pocketmine\form\Form;
use pocketmine\Player;

class MailMainOPForm implements Form{

    private $button = [

        ["text" => "メールを一斉送信する"],
        ["text" => "運営あてのメールを見る"],
        ["text" => "運営あてのメールを削除する"]
    ];

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        switch($data){
            case 0:
                $player->sendForm(new MailSendOPForm());
            break;

            case 1:
                $player->sendForm(new MailShowSelectOPForm());
            break;

            case 2:
                $player->sendForm(new MailRemoveSelectOPForm());
            break;
        }
    }

    public function jsonSerialize(){

        return [

            "type" => "form",
            "title" => "§cMailAdmin",
            "content" => "操作を選択してください",
            "buttons" => $this->button

        ];
    }
}
