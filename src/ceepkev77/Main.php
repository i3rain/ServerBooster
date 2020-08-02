<?php

declare(strict_types=1);

namespace ceepkev77;

use pocketmine\block\BlockFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\Item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use jojoe77777\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use DateTime;
use DateInterval;

class Main extends PluginBase implements Listener {

    public $bsek = 1200;
    public $fsek = 1200;
    public $sek = 0;
    public $bba = false;
    public $fba = false;
    public $prefix = "§bBooster§c §8»§r§l ";
    /** @var string */
    public $noperm = TextFormat::RED . "Du benötigst einen Rang um den Booster zu aktivieren!";
    public $helpHeader =
        TextFormat::AQUA . "--- " .
        TextFormat::AQUA . "[" . TextFormat::RED . "Server Booster" . TextFormat::AQUA . "] " .
        TextFormat::AQUA . "---";
    /** @var string[] */
    public $mainArgs = [
        "help: /booster help",
        "feed: /booster feed",
        "heal: /booster heal",
        "fly: /booster fly",
        "break: /booster break",
        "version: /booster version",
    ];

    /**
     * @return void
     */
    public function onEnable(): void {
        $this->getLogger()->info("Booster wurde aktiviert");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $config = new Config($this->getDataFolder()."boostercheck.yml", Config::YAML);
        $config->set("FlyBooster", false);
                            $config->save();
        $config->set("BreakBooster", false);
                            $config->save();
        //$effect = new EffectInstance(Effect::getEffect(3), 999999999, 3, false);
		$cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
		$cooldown->save();
    }   

    public function onJoinBooster(PlayerJoinEvent $event) {
        $config = new Config($this->getDataFolder()."boostercheck.yml", Config::YAML);
        if ($config->get("FlyBooster") == true) {
            $player = $event->getPlayer();
            $player->setAllowFlight(TRUE);
            $player->sendMessage("§bBooster§c §8»§r §aDer FlyBooster ist aktiv, daher kannst du Fliegen.");
        } else {
            $player = $event->getPlayer();
           $player->setAllowFlight(FALSE);
           $player->sendMessage("§bBooster§c §8»§r §cDer FlyBooster ist deaktiviert.");
	   $player = $event->getPlayer();
          if($player->getGamemode() == 1) {
            $player->setAllowFlight(TRUE);
          } 
        }
        if ($config->get("BreakBooster") == true) {
            $player = $event->getPlayer();
            $effect = new EffectInstance(Effect::getEffect(3), 999999999, 3, false);
            $player->addEffect($effect);
            $player->sendMessage("§bBooster§c §8»§r §aDer BreakBooster ist aktiv, daher kannst du schneller abbauen.");
        } else {
            $player = $event->getPlayer();
            $player->sendMessage("§bBooster§c §8»§r §cDer BreakBooster ist deaktiviert.");
            $player->removeAllEffects();
        }
    }
	
        
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        $config = new Config($this->getDataFolder()."boostercheck.yml", Config::YAML);
        switch (strtolower($command->getName())) {
            
            case "booster":
                if ($sender instanceof Player) {
                    if (!isset($args[0])) {
                        if (!$sender->hasPermission("booster.command")) {
                            $sender->sendMessage($this->noperm);
                            return true;
                        } else {
                            
        $form = new SimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 0:
                    
                        break;
                    case 1:
                    $command = "booster help";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
						break;
						           case 2:
                    $command = "booster feed";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                   case 3:
                    $command = "booster heal";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                     case 4:
                    $command = "booster fly";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                                     case 5:
                    $command = "booster break";
								$this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
                            }
             });
        $form->setTitle("§l§b Booster");
        $form->setContent("§6Hier kannst du §1Booster den Booster aktivieren!");
        $form->addButton("§4Abbruch", 0);
        $form->addButton("§dHelp", 1);
        $form->addButton("§dFeed", 2);
        $form->addButton("§dHeal", 3);
        $form->addButton("§dFly", 4);
        $form->addButton("§dBreak", 5);
        $form->sendToPlayer($sender);
        }
        return true;
                    }
                    $arg = array_shift($args);
                    switch ($arg) {
                        case "version":
                            if (!$sender->hasPermission("booster.version")) {
                                $sender->sendMessage($this->noperm);
                                return true;
                            }
                            $sender->sendMessage($this->prefix . "§r" . TextFormat::BLUE . "Booster Version 1.2.0 von ceepkev77");
                            return true;
                        case "help":
                        case "?":
                            $sender->sendMessage($this->helpHeader);
                            foreach ($this->mainArgs as $msgArg) {
                                $sender->sendMessage(TextFormat::AQUA . " - " . $msgArg . "\n");
                            }
                            return true;
                        case "feed":
                            if (!$sender->hasPermission("booster.feed")) {
                                 $sender->sendMessage($this->noperm);
                                return true;
                            }
                            if ($sender->hasPermission("booster.bypass")) {
                            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                 $p->setFood(20);
                            }
                                 $player = $sender->getName();
                                 $this->getServer()->broadcastMessage($this->prefix . "§fDer §fFeed Booster §fwurde von §6$player §faktiviert.");
                                return true;
                            }elseif ($sender->hasPermission("booster.feed")){
                        		$cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                                if (!$cooldown->exists($sender->getName() . "Feed")){
                                   $cooldown->set($sender->getName() . "Feed", date('Y-m-d H:i:s'));
                                   $cooldown->save();
                                }
								$cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
								$last = new DateTime($cooldown->get($sender->getName() . "Feed"));
								if (new DateTime("now") > $last) {
									foreach ($this->getServer()->getOnlinePlayers() as $p) {
										$p->setFood(20);
                                    }
                                    $player = $sender->getName();
									$date = new DateTime('+1 day');
									$cooldown->set($sender->getName() . "Feed", $date->format('Y-m-d H:i:s'));
									$cooldown->save();
									$sender->sendMessage($this->prefix . "§aAm §c" . $date->format('Y-m-d H:i:s') . " §akannst du deinen nächsten Feed Booster abholen!");	
									$this->getServer()->broadcastMessage($this->prefix . "§aDer §bFeedBooster §awurde von §b$player §aaktiviert.");
                                }else{
									$sender->sendMessage($this->prefix . "§aAm §c" . $cooldown->get($sender->getName() . "Feed") . " §akannst du erst deinen nächsten Feed Booster aktivieren!");
                                }
                            }	
                            return true;
                        case "heal":
                             if (!$sender->hasPermission("booster.heal")) {
                                 $sender->sendMessage($this->noperm);
                                return true;
                            }
                            if ($sender->hasPermission("booster.bypass")) {
                            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                 $p->setHealth(20);
                            }
                                 $player = $sender->getName();
                                 $this->getServer()->broadcastMessage($this->prefix . "§aDer §bHealBooster §awurde von §b$player §aaktiviert.");
                                return true;
                            }elseif ($sender->hasPermission("booster.heal")){
                                $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                                if (!$cooldown->exists($sender->getName() . "Heal")){
                                   $cooldown->set($sender->getName() . "Heal", date('Y-m-d H:i:s'));
                                   $cooldown->save();
                                }
                                $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                                $last = new DateTime($cooldown->get($sender->getName() . "Heal"));
                                if (new DateTime("now") > $last) {
                                    foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                        $p->setHealth(20);
                                    }
                                    $player = $sender->getName();
                                    $date = new DateTime('+1 day');
                                    $cooldown->set($sender->getName() . "Heal", $date->format('Y-m-d H:i:s'));
                                    $cooldown->save();
                                    $sender->sendMessage($this->prefix . "§aAm §c" . $date->format('Y-m-d H:i:s') . " §akannst du deinen nächsten HealBooster aktivieren!");	
                                    $this->getServer()->broadcastMessage($this->prefix . "§aDer §bHealBooster §awurde von §b$player §aaktiviert.");
                                }else{
                                    $sender->sendMessage($this->prefix . "§aAm §c" . $cooldown->get($sender->getName() . "Heal") . " §akannst du erst wieder einen HealBooster aktivieren!");
                                }
                            }	
                            return true;
                        case "fly":
                            if (!$sender->hasPermission("booster.fly")) {
                                $sender->sendMessage($this->noperm);
                               return true;
                           }
                           if ($config->get("FlyBooster") == true){
                            $sender->sendMessage($this->prefix . "§cMomentan läuft ein FlyBooster!");
                            return true;
                            }
                           if ($sender->hasPermission("booster.bypass")) {
                            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                $p->setAllowFlight(TRUE);
                           }
                           $player = $sender->getName();
                           $config->set("FlyBooster", true);
                           $config->save();
                              $this->getServer()->broadcastMessage($this->prefix . "§aDer §bFlyBooster §awurde von §b$player §aaktiviert.\n§r§aDu kannst nun dank dem FlyBooster von §b$player §afliegen.");
                              $this->getScheduler()->scheduleRepeatingTask(new FlyBooster($this), 30);
                   return true;
                           }elseif ($sender->hasPermission("booster.fly")){
                               $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                               if (!$cooldown->exists($sender->getName() . "Fly")){
                                  $cooldown->set($sender->getName() . "Fly", date('Y-m-d H:i:s'));
                                  $cooldown->save();
                               }
                               $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                               $last = new DateTime($cooldown->get($sender->getName() . "Fly"));
                               if (new DateTime("now") > $last) {
                                   foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                       $p->setAllowFlight(TRUE);
                                   }
                                   $player = $sender->getName();
                                   $date = new DateTime('+1 day');
                                   $cooldown->set($sender->getName() . "Fly", $date->format('Y-m-d H:i:s'));
                                   $cooldown->save();
                                   $sender->sendMessage($this->prefix . "§aAm §c" . $date->format('Y-m-d H:i:s') . " §akannst du deinen nächsten FlyBooster aktivieren!");	
                                   $config->set("FlyBooster", true);
                                   $config->save();
                                      $this->getServer()->broadcastMessage($this->prefix . "§aDer §bFlyBooster §awurde von §b$player §aaktiviert.\n§r§aDu kannst nun dank dem FlyBooster von §b$player §afliegen.");
                                      $this->getScheduler()->scheduleRepeatingTask(new FlyBooster($this), 30);
                               }else{
                                   $sender->sendMessage($this->prefix . "§aAm §c" . $cooldown->get($sender->getName() . "Fly") . " §akannst du erst wieder einen FlyBooster aktivieren!");
                               }
                           }	
                           return true;
                        case "break":
                            if (!$sender->hasPermission("booster.break")) {
                                $sender->sendMessage($this->noperm);
                               return true;
                           }
                           if ($config->get("BreakBooster") == true){
                            $sender->sendMessage($this->prefix . "§cMomentan läuft ein FlyBooster!");
                            return true;
                            }
                           if ($sender->hasPermission("booster.bypass")) {
                            foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                $effect = new EffectInstance(Effect::getEffect(3), 999999999, 3, false);
                                $p->addEffect($effect);
                           }
                           $player = $sender->getName();
                           $config->set("BreakBooster", true);
                           $config->save();
                              $this->getServer()->broadcastMessage($this->prefix . "§aDer §bBreakBooster §awurde von §b$player §aaktiviert.\n§r§aDu kannst nun dank dem BreakBooster von §b$player §aschneller abbauen.");
                              $this->getScheduler()->scheduleRepeatingTask(new BreakBooster($this), 30);
                   return true;
                           }elseif ($sender->hasPermission("booster.break")){
                               $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                               if (!$cooldown->exists($sender->getName() . "Break")){
                                  $cooldown->set($sender->getName() . "Break", date('Y-m-d H:i:s'));
                                  $cooldown->save();
                               }
                               $cooldown = new Config($this->getDataFolder().'cooldown.yml', Config::YAML);
                               $last = new DateTime($cooldown->get($sender->getName() . "Break"));
                               if (new DateTime("now") > $last) {
                                   foreach ($this->getServer()->getOnlinePlayers() as $p) {
                                       $effect = new EffectInstance(Effect::getEffect(3), 999999999, 3, false);
                                       $p->addEffect($effect);
                                   }
                                   $player = $sender->getName();
                                   $date = new DateTime('+1 day');
                                   $cooldown->set($sender->getName() . "Break", $date->format('Y-m-d H:i:s'));
                                   $cooldown->save();
                                   $sender->sendMessage($this->prefix . "§aAm §c" . $date->format('Y-m-d H:i:s') . " §akannst du deinen nächsten BreakBooster aktivieren!");	
                                   $config->set("BreakBooster", true);
                                   $config->save();
                                      $this->getServer()->broadcastMessage($this->prefix . "§aDer §bBreakBooster §awurde von §b$player §aaktiviert.\n§r§aDu kannst nun dank dem BreakBooster von §b$player §aschneller abbauen.");
                                      $this->getScheduler()->scheduleRepeatingTask(new BreakBooster($this), 30);
                               }else{
                                   $sender->sendMessage($this->prefix . "§aAm §c" . $cooldown->get($sender->getName() . "Break") . " §akannst du erst wieder einen BreakBooster aktivieren!");
                               }
                           }	
                           return true;
    } 
   }
  }
  return true;
 }
}
