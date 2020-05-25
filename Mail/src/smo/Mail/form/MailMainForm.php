<?php

declare(strict_types=1);

namespace smo\Mail\form;

use pocketmine\form\Form;
use pocketmine\Player;

class MailMainForm implements Form{

    private $button = [

        ["text" => "メールを送る"],
        ["text" => "メールを見る"],
        ["text" => "メールを削除する"]
    ];

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        switch($data){
            case 0:
                $player->sendForm(new MailSendForm());
            break;

            case 1:
                $player->sendForm(new MailShowSelectForm($player));
            break;

            case 2:
                $player->sendForm(new MailRemoveSelectForm($player));
            break;
        }
    }

    public function jsonSerialize(){

        return [

            "type" => "form",
            "title" => "Mail",
            "content" => "操作を選択してください",
            "buttons" => $this->button

        ];
    }
}
