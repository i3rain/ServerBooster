<?php

namespace ceepkev77;


use pocketmine\level\sound\PopSound;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as f;

class BreakBooster extends Task
{
    protected $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun(int $currentTick) : void{
        $config = new Config($this->plugin->getDataFolder()."boostercheck.yml", Config::YAML);
        $this->plugin->bsek--;
            if($this->plugin->bsek === 30) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                         $p->sendMessage("§bBooster§c §8» §l§aDer BreakBooster wird in §c30 sekunden §adeaktiviert.");
                         }
            }elseif($this->plugin->bsek === 10) {
            foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                         $p->sendMessage("§bBooster§c §8» §l§aDer BreakBooster wird in §c10 sekunden §adeaktiviert.");
                         }
            }elseif($this->plugin->bsek === 0) {
                foreach ($this->plugin->getServer()->getOnlinePlayers() as $p) {
                         $p->sendMessage("§bBooster§c §8» §l§aDer BreakBooster wurde nun deaktiviert. \n\n§r§aDu kannst nun nicht mehr schneller abbauen.");
                    $p->removeAllEffects();
                    $p->getLevel()->addSound(new PopSound($p));
                    $config->set("BreakBooster", false);
                            $config->save();
                }
            }
        }
    }
