<?php

declare(strict_types=1);

namespace smo\Mail\form;

use pocketmine\form\Form;
use pocketmine\Player;
use smo\Mail\System;

class MailRemoveSelectForm implements Form{

    private $button;
    private $idList;
    private $content;

    public function __construct(Player $player){

        $system = System::get();

        if(count($system->getPlayerMail($player->getName())) === 0){
            $this->button[] = ["text" => "終了"];
            $this->content = "あなた宛てのメールはないようです....";
        }else{
            foreach($system->getPlayerMail($player->getName()) as $index => $id){
                $data = $system->getData($id);
                if($data === false) continue;
                $this->button[] = ["text" => $data["title"]."\n".$data["senderName"]];
                $this->idList[] = $id;
            }
            $this->content = "削除するメールを選択してください\n§c選択したメールは削除され元に戻せません！§r";
        }
    }

    public function handleResponse(Player $player, $data): void {

        if($data === null) return;

        if($this->idList === null){
            return;
        }else{
            System::get()->removeMail($this->idList[$data]);
            $player->sendMessage("§a[Mail] >> メールを削除しました");
        }

    }

    public function jsonSerialize(){

        return [

            "type" => "form",
            "title" => "Mail",
            "content" => $this->content,
            "buttons" => $this->button

        ];
    }
}
