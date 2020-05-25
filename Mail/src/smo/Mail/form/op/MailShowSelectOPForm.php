<?php

declare(strict_types=1);

namespace smo\Mail\form\op;

use pocketmine\form\Form;
use pocketmine\Player;
use smo\Mail\System;

class MailShowSelectOPForm implements Form{

    private $button;
    private $idList;
    private $content;

    public function __construct(){

        $system = System::get();

        if(count($system->getPlayerMail("Admin")) === 0){
            $this->button[] = ["text" => "終了"];
            $this->content = "運営宛てのメールはないようです...";
        }else{
            foreach($system->getPlayerMail("Admin") as $index => $id){
                $data = $system->getData($id);
                if($data === false) continue;
                $this->button[] = ["text" => $data["title"]."\n".$data["senderName"]];
                $this->idList[] = $id;
            }
            $this->content = "見るメールを選択してください";
        }
    }

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        if($this->idList === null){
            return;
        }else{
            $player->sendForm(new MailShowOPForm($this->idList[$data]));
        }
    }

    public function jsonSerialize(){

        return [

            "type" => "form",
            "title" => "§cMailAdmin",
            "content" => $this->content,
            "buttons" => $this->button

        ];
    }
}
