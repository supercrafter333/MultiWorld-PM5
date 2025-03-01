<?php

/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2022  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace czechpmdevs\multiworld\command\subcommand;

use czechpmdevs\multiworld\MultiWorld;
use czechpmdevs\multiworld\util\LanguageManager;
use czechpmdevs\multiworld\util\WorldUtils;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\world\World;
use function file_exists;
use function str_replace;

class DeleteSubCommand implements SubCommand {

	public function execute(CommandSender $sender, array $args, string $name): void {
		if(!isset($args[0])) {
			$sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::translateMessage($sender, "delete-usage"));
			return;
		}

		if(!Server::getInstance()->getWorldManager()->isWorldGenerated($args[0]) || !file_exists(Server::getInstance()->getDataPath() . "worlds/$args[0]")) {
			$sender->sendMessage(MultiWorld::getPrefix() . str_replace("%1", $args[0], LanguageManager::translateMessage($sender, "delete-levelnotexists")));
			return;
		}

		$world = Server::getInstance()->getWorldManager()->getWorldByName($args[0]);
		if($world instanceof World) { // World is loaded
			if(WorldUtils::getDefaultWorldNonNull()->getId() == $world->getId()) {
				$sender->sendMessage("§cCould not remove default world!");
				return;
			}
		}

		$sender->sendMessage(MultiWorld::getPrefix() . LanguageManager::translateMessage($sender, "delete-done", [((string)WorldUtils::removeWorld($args[0]))]));
	}
}
