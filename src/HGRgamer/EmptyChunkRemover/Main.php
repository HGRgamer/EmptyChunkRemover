<?php

declare(strict_types=1);

namespace HGRgamer\EmptyChunkRemover;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\leveldb\LevelDB;
use pocketmine\world\World;

class Main extends PluginBase
{

    private const CURRENT_CONFIG_VERSION = "1.0";
    private Config $config;

    public function onEnable(): void
    {
        $this->checkAndUpdateConfig();
        
        $worlds = $this->config->get("worlds");
        if (empty($worlds)) {
            $this->getLogger()->error("No worlds found in config.yml");
            return;
        }
        if (!is_array($worlds)) {
            $this->getLogger()->error("Invalid config.yml, worlds must be an array");
            return;
        }
        
        $this->getLogger()->warning("This is a performance heavy and a thread blocking process");
        $this->getLogger()->warning("World conversion may take a long time (few mins to many hours) depending on world size, please be patient");

        $this->fixWorlds($worlds);
    }

    private function checkAndUpdateConfig(): void
    {
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();

        // Check the config version
        $configVersion = $this->config->get("config-version", "");
        if ($configVersion !== self::CURRENT_CONFIG_VERSION) {
            $this->getLogger()->warning("Config version mismatch or missing. Updating to the latest config.");
            $this->replaceConfigWithDefault();
        }
    }

    private function replaceConfigWithDefault(): void
    {
        $configFile = $this->getDataFolder() . "config.yml";

        // Backup the old config if it exists
        if (file_exists($configFile)) {
            $backupFile = $this->getDataFolder() . "config-backup.yml";
            copy($configFile, $backupFile);
        }

        // Save the default config from the resources
        $this->saveResource("config.yml", true);

        // Reload the updated config
        $this->reloadConfig();
        $this->config = $this->getConfig();
    }

    private function getLoadedWorld(string $worldName): bool|World
    {
        if (!$this->getServer()->getWorldManager()->isWorldGenerated($worldName)) {
            $this->getLogger()->error("No world found with name $worldName in worlds folder");
            return false;
        }

        if ($this->getServer()->getWorldManager()->loadWorld($worldName) === false) {
            $this->getLogger()->error("Failed to load world $worldName");
            return false;
        }
        $world = $this->getServer()->getWorldManager()->getWorldByName($worldName);
        if (!$world instanceof World) {
            $this->getLogger()->error("Failed to get world $worldName");
            return false;
        }
        return $world;
    }

    private function fixWorlds(array $worlds) : void{
        foreach ($worlds as $worldName) {

            $world = $this->getLoadedWorld($worldName);
            if(!$world) continue;

            $path = $this->getDataFolder() . $worldName;
            if (file_exists($path)) {
                $this->getLogger()->info("Folder $path already exists, skipping world $worldName");
                continue;
            }

            $startTime = microtime(true);

            mkdir($path, 0777, true);
            //copy all files from world folder to plugin data folder , no need for folders
            $worldPath = $this->getServer()->getDataPath() . "worlds/" . $worldName;
            foreach (scandir($worldPath) as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                if (is_dir($worldPath . "/" . $file)) {
                    continue;
                }
                copy($worldPath . "/" . $file, $path . "/" . $file);
            }

            $leveldbNew = new LevelDB($path, $this->getLogger());

            $totalChunks = $world->getProvider()->calculateChunkCount();
            $emptyChunksCount = 0;
            foreach ($world->getProvider()->getAllChunks(true) as $coords => $loadedChunkData) {
                [$chunkX, $chunkZ] = $coords;

                $subChunksCount = 0;
                $emptySubChunksCount = 0;
                foreach ($loadedChunkData->getData()->getSubChunks() as $subChunk) {
                    $subChunksCount++;
                    if ($subChunk->isEmptyAuthoritative()) {
                        $emptySubChunksCount++;
                    }
                }
                if ($subChunksCount === $emptySubChunksCount) {
                    $emptyChunksCount++;
                    continue;
                }
                $leveldbNew->saveChunk($chunkX, $chunkZ, $loadedChunkData->getData(), Chunk::DIRTY_FLAGS_ALL);
            }
            $leveldbNew->close();

            $timeTaken = round((microtime(true) - $startTime) / 60, 2);
            $timeMsg = ($timeTaken < 5 ? $timeTaken * 60 . " seconds" : "$timeTaken minutes");
            $this->getLogger()->info("Removed $emptyChunksCount empty chunks out of $totalChunks from $worldName. Remaning chunks: " . ($totalChunks - $emptyChunksCount) . ". Time taken: $timeMsg");

            $this->getLogger()->info("Fixed $worldName is saved inside plugin's data folder at $path");
        }

    }
}
