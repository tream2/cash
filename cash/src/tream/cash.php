<?php

namespace tream;
//서버 기본 선언
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
// 이벤트 선언
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
// 패킷 선언
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;

class cash extends PluginBase implements Listener{
   private static $instance;
   public function onEnable(){
      @mkdir ( $this->getDataFolder () );
      $this->data = new Config ( $this->getDataFolder () . "cash.yml", Config::YAML);
      $this->db = $this->data->getAll ();
      $this->getServer()->getPluginManager()->registerEvents($this,$this);
   }
   public function onDisable(){
   	$this->data->setAll($this->db);
    $this->data->save();
   }
  public function onLoad() {
    self::$instance = $this;
  }
  public static function getInstance() : Occupation{
    return self::$instance;
  }
  public function MainData($info){
     $MainData = [ 
            "type" => "form",
            "title" => "§l:: Cash !! ::",
            "content" => "§l{$info}", 
            "buttons" => [
                [
                  "text" => "§l[ 나가기 ]§r§f",
                ],
                [
                  "text" => "§l[ 내 캐쉬 ]§r§f",
                ],
                [
                  "text" => "§l[ 캐쉬 보기 ]§r",
                ],
                [
                  "text" => "§l[ 캐쉬 주기 ]§r"
                ]
            ]
        ];
        return json_encode($MainData);
  }
   public function see(){
        $see = [
            "type" => "custom_form",
            "title" => ":: 캐쉬 보기 ::",
            "content" => [
              [
               "type" => "input",
               "text" => "§l보고싶은 유저의 닉네임을 적어주세요!",
              ]
            ]
        ];
      return json_encode($see);
   }
   public function give(){
        $give = [
            "type" => "custom_form",
            "title" => ":: 캐쉬 주기 ::",
            "content" => [
              [
               "type" => "input",
               "text" => "§l주고싶은 유저의 닉네임을 적어주세요!",
              ],
              [
                "type" => "input",
                "text" => "§l줄 캐쉬양을 적어주세요!",
              ]
            ]
        ];
      return json_encode($give);
   }
   public function bite(){
        $give = [
            "type" => "custom_form",
            "title" => ":: 캐쉬 뺏기 ::",
            "content" => [
              [
               "type" => "input",
               "text" => "§l뺏고싶은 유저의 닉네임을 적어주세요!",
              ],
              [
                "type" => "input",
                "text" => "§l뺏을 캐쉬양을 적어주세요!",
              ]
            ]
        ];
      return json_encode($give);
   }
  public function Complete($info){
     $Complete = [ 
            "type" => "form",
            "title" => "§l:: 캐쉬 확인 ::",
            "content" => "§l{$info}", 
            "buttons" => [ ]
        ];
        return json_encode($Complete);
  }
  public function 메뉴목록(DataPacketReceiveEvent $event){
  	$pack = $event->getPacket ();
    $player = $event->getPlayer();
	$pname = $player->getName();
		if ($pack instanceof ModalFormResponsePacket) {
		    if($pack->formId == 987){
				$name = json_decode ( $pack->formData, true );

				if($name == 0){
				}
				if($name == 1){
					$info = ":: {$pname}님의 캐쉬를 확인 합니다 ::\n- 닉네임 : {$pname}\n- 보유중인 캐쉬 : {$this->db [strtolower($pname)] ["cash"]}원";
					$this->sendUI($player, 986, $this->Complete($info));
				}
				if($name == 2){
					$this->sendUI($player, 985, $this->see());
				}
				if($name == 3){
					$this->sendUI($player, 984, $this->give());
				}
			}
		}
	}
    public function 캐쉬보기(DataPacketReceiveEvent $event) {
      $pack = $event->getPacket ();
       $player = $event->getPlayer();
      $pname = $player->getName();
      if ($pack instanceof ModalFormResponsePacket) {
            if($pack->formId == 985){
               $name = json_decode ( $pack->formData, true );

               if($name[0] == null){
               	$player->sendMessage("닉네임을 적어주세요.");
               	return true;
               }
               if(! is_numeric($name[1])){
                $player->sendMessage("숫자로 적어주세요");
                return true;;
               }
               if($name[0]){
               	if(!isset($this->db [strtolower($name[0])] ["cash"])){
               		$player->sendMessage("서버에서 그런 유저를 찾아볼 수 없습니다.");
               		return true;
               	}
               	$n = strtolower($name[0]);
               	$info = "§l:: {$n}님의 캐쉬를 확인 합니다 ::\n- 닉네임 : {$n}\n- 보유중인 캐쉬 : {$this->db [$n] ["cash"]}원";
               	$this->sendUI($player, 983, $this->Complete($info));
               }
            }
        }
    }
    public function 캐쉬주기(DataPacketReceiveEvent $event) {
      $pack = $event->getPacket ();
       $player = $event->getPlayer();
      $pname = $player->getName();
      if ($pack instanceof ModalFormResponsePacket) {
            if($pack->formId == 984){
               $name = json_decode ( $pack->formData, true );

               if($name[0] == null & $name[1] == null){
               	$player->sendMessage("양식에 맞게 적어주세요.");
               	return true;
               }
               if(! is_numeric($name[1])){
                $player->sendMessage("숫자로 적어주세요");
                return true;;
               }
               if($name[0]){
               	if(!isset($this->db [strtolower($name[0])] ["cash"])){
               		$player->sendMessage("서버에서 그런 유저를 찾아볼 수 없습니다.");
               		return true;
               	}
               	if($name[1] > $this->db [strtolower($pname)] ["cash"]){
               		$player->sendMessage("보유하고 있는 캐쉬가 부족합니다.");
               		return true;
               	}
               	$this->removeCash($pname, $name[1]);
               	$this->addCash($name[0], $name[1]);
               	$info = "§l:: {$name[0]}님에게 {$name[1]}의 캐쉬를 줍니다 ::\n- 닉네임 : {$name[0]}\n- 지불 할 캐쉬 : {$name[1]}원\n지불을 완료 하였습니다!";
               	$this->sendUI($player, 983, $this->Complete($info));
               }
            }
        }
    }    
    public function 관리자주기(DataPacketReceiveEvent $event) {
      $pack = $event->getPacket ();
       $player = $event->getPlayer();
      $pname = $player->getName();
      if ($pack instanceof ModalFormResponsePacket) {
            if($pack->formId == 989){
               $name = json_decode ( $pack->formData, true );

               if($name[0] == null & $name[1] == null){
                $player->sendMessage("양식에 맞게 적어주세요.");
                return true;
               }
               if(! is_numeric($name[1])){
                $player->sendMessage("숫자로 적어주세요");
                return true;;
               }
               if($name[0]){
                if(!isset($this->db [strtolower($name[0])] ["cash"])){
                  $player->sendMessage("서버에서 그런 유저를 찾아볼 수 없습니다.");
                  return true;
                }
                $this->addCash($name[0], $name[1]);
                $info = "§l:: {$name[0]}님에게 {$name[1]}의 캐쉬를 줍니다 ::\n- 닉네임 : {$name[0]}\n- 지불 할 캐쉬 : {$name[1]}원\n지불을 완료 하였습니다!";
                $this->sendUI($player, 983, $this->Complete($info));
               }
            }
        }
    }
    public function 관리자뻇기(DataPacketReceiveEvent $event) {
      $pack = $event->getPacket ();
       $player = $event->getPlayer();
      $pname = $player->getName();
      if ($pack instanceof ModalFormResponsePacket) {
            if($pack->formId == 990){
               $name = json_decode ( $pack->formData, true );

               if($name[0] == null & $name[1] == null){
                $player->sendMessage("양식에 맞게 적어주세요.");
                return true;
               }
               if(! is_numeric($name[1])){
                $player->sendMessage("숫자로 적어주세요");
                return true;;
               }
               if($name[0]){
                if(!isset($this->db [strtolower($name[0])] ["cash"])){
                  $player->sendMessage("서버에서 그런 유저를 찾아볼 수 없습니다.");
                  return true;
                }
                $this->removeCash($name[0], $name[1]);
                $info = "§l:: {$name[0]}님에게 {$name[1]}의 캐쉬를 뺏습니다 ::\n- 닉네임 : {$name[0]}\n- 뺏을 할 캐쉬 : {$name[1]}원\n뺏었습니다.";
                $this->sendUI($player, 983, $this->Complete($info));
               }
            }
        }
    }
   public function onCommand(CommandSender $sender, Command $command,string $label, array $args) : bool{
         $name = $sender->getName();
		if($command->getName() == "캐쉬"){
			$info = "§l보유한 캐쉬 : {$this->db [strtolower($name)] ["cash"]}원";
			$this->sendUI($sender, 987, $this->MainData($info));
			return true;
		}
    if($command->getName() == "캐쉬관리"){
      if(!isset($args[0])){
        $sender->sendMessage("/캐쉬관리 주기");
        $sender->sendMessage("/캐쉬관리 뺏기");
        return true;
      }
      switch($args[0]){
        case "주기":
        $this->sendUI($sender, 989, $this->give());
        return true;

        case "뺏기":
        $this->sendUI($sender, 990, $this->bite());
        return true;
      }
      return true;
    }
   }
   public function onJoin(PlayerJoinEvent $event){
      $player = $event->getPlayer();
      $name = $player->getName();
      if(!isset($this->db [$name])){
        $this->db [strtolower($name)] ["cash"] = 0;
        $this->onSave();
        return true;
      }
   }
    public function sendUI(Player $player, $code, $data){
        $pk = new ModalFormRequestPacket();
        $pk->formId = $code;
        $pk->formData = $data;
        $player->dataPacket($pk);
    }
    public function onSave (){
      $this->data->setAll($this->db);
      $this->data->save();
    }
  public function addCash($player, $amount){
  	$player = $player instanceof Player ? $player->getName() : $player;
    if(isset($this->db [strtolower($player)])){
      $this->db [strtolower($player)] ["cash"] += $amount;
      $this->onSave();
    }
  }
  public function removeCash($player, $amount){
  	$player = $player instanceof Player ? $player->getName() : $player;
    if(isset($this->db [strtolower($player)])){
      $this->db [strtolower($player)] ["cash"] -= $amount;
      $this->onSave();
    }
  }
  public function setCash($player, $amount){
  	$player = $player instanceof Player ? $player->getName() : $player;
    if(isset($this->db [strtolower($player)])){
      $this->db [strtolower($player)] ["cash"] = $amount;
      $this->onSave();
    }
  }
  public function seeCash($player){
    $player = $player instanceof Player ? $player->getName() : $player;
    if(isset($this->db [strtolower($player)])){
      return $this->db [strtolower($player)] ["cash"];
    }
  }
}
?>
