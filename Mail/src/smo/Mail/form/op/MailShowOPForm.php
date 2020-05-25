<?php

declare(strict_types=1);

namespace smo\Mail\form\op;

use pocketmine\form\Form;
use pocketmine\Player;
use smo\Mail\System;

class MailShowOPForm implements Form{

    private $data;

    public function __construct(int $id){

        $this->data = System::get()->getData($id);
    }

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        $player->sendForm(new MailShowSelectOPForm($player));
    }

    public function jsonSerialize(){

        $content = "件名: ".$this->data["title"]."\n送信者: ".$this->data["senderName"]."\n送信日: ".$this->data["date"]."\n\n".$this->data["message"]."\n";

        return [

            "type" => "form",
            "title" => "§cMailAdmin",
            "content" => $content,
            "buttons" => [["text" => "戻る"]]

        ];
    }
}
