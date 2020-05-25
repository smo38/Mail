<?php

declare(strict_types=1);

namespace smo\Mail;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class System{

    private $mailData;
    private $playerData;

    private static $instance = null;
    private $plugin;

    public function __construct($plugin){

        date_default_timezone_set("Asia/Tokyo");

        $this->plugin = $plugin;
        $this->mailData = new Config($plugin->getDataFolder() . "MailData.yml", Config::YAML, [

            "ID" => 1

        ]);
        $this->playerData = new Config($plugin->getDataFolder() . "PlayerData.yml", Config::YAML);

        self::$instance = $this;

        if(!$this->existsPlayer("Admin")) $this->registerPlayer("Admin");
    }

    public static function get(): self {

        return self::$instance;
    }

    public function saveConfig(): void {

        $this->mailData->save();
        $this->playerData->save();
    }

    public function existsPlayer(string $name): bool {

        return $this->playerData->exists($name);
    }

    public function registerPlayer(string $name): void {

        $this->playerData->set($name, []);
        $this->playerData->save();
    }

    public function getID(): int {

        $id = (int) $this->mailData->get("ID");
        $this->mailData->set("ID", $id + 1);

        return $id;
    }

    private function addMail(string $senderName, string $title, string $message): int {

        $id = $this->getID();
        $date = date("Y年m月d日H時i分s秒");

        $this->mailData->set($id, [

            "senderName" => $senderName,
            "title" => $title,
            "message" => $message,
            "date" => $date
        ]);

        return $id;
    }

    public function getPlayerMail(string $name): array {

        return $this->playerData->get($name); //int[]?
    }

    public function sendMail(Player $sender, string $name, string $title, string $message): void {

        if(!$this->existsPlayer($name)){
            $sender->sendMessage("§c[Mail] >> ".$name."のデータがありません");
            return;
        }

        $senderName = $sender->getName();
        $message = str_replace("#", "\n", $message);

        $id = $this->addMail($senderName, $title, $message);
        $array = $this->getPlayerMail($name);
        array_push($array, $id);
        $this->playerData->set($name, $array);

        $player = Server::getInstance()->getPlayer($name);

        if($player instanceof Player){
            $player->sendMessage("§a[Mail] >> ".$senderName."さんから新規メールが届きました");
        }
    }

    public function sendAll(string $title, string $message): void {

        foreach($this->playerData->getAll(true) as $name){
            if($name === "Admin") continue;
            $this->sendMailBySystem($name, $title, $message);
        }
    }

    public function sendMailBySystem(string $name, string $title, string $message, string $senderName = "運営"): void {


        $message = str_replace("#", "\n", $message);

        $id = $this->addMail($senderName, $title, $message);
        $array = $this->getPlayerMail($name);
        array_push($array, $id);
        $this->playerData->set($name, $array);

        $player = Server::getInstance()->getPlayer($name);

        if($player instanceof Player){
            $player->sendMessage("§a[Mail] >> ".$senderName."さんから新規メールが届きました");
        }
    }

    public function removeMail(int $id): void {

        if(!$this->mailData->exists($id)) return;

        $this->mailData->remove($id);

        foreach($this->playerData->getAll() as $name => $data){
            foreach($data as $index => $int){
                if($int === $id){
                    unset($data[$index]);
                }
            }

            $this->playerData->set($name, $data);
        }
    }

    public function getData(int $id){

        return $this->mailData->get($id);
    }
}
