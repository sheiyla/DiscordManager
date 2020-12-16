<?php


namespace App\Http\Helpers;

use GuzzleHttp\Command\Exception\CommandClientException;
use RestCord\DiscordClient;

class DiscordUtils
{
    /**
     * Check if the bot is in the specified guild with id
     * @param $guildId
     * @return bool
     */
    public static function isBotInGuild($guildId)
    {
        $guilds = collect(app(DiscordClient::class)->user->getCurrentUserGuilds());
        return $guilds->map(function ($guild) {
            return $guild->id;
        })->contains($guildId);
    }

    /**
     * Check if the bot is in the specified guild with id
     * @param $guildsId
     * @return array
     */
    public static function isBotInGuilds($guildsId)
    {
        $guilds = collect(app(DiscordClient::class)->user->getCurrentUserGuilds());
        return $inGuildList = $guilds->map(function ($guild) {
            return $guild->id;
        })->intersect($guildsId)->all();
    }

    /**
     * Add roles to members
     * @param $guildId
     * @param $usersId
     * @param $rolesId
     */
    public static function addGuildMembersRoles($guildId, $usersId, $rolesId)
    {
        $results = [];
        foreach ($usersId as $userId) {
            foreach ($rolesId as $roleId) {
                try {
//                    new DiscordClient()->
                    app(DiscordClient::class)->guild->addGuildMemberRole(['guild.id' => intval($guildId), 'user.id' => intval($userId), 'role.id' => intval($roleId)]);
                } catch (CommandClientException $exception) {
                    $results[$userId] = self::handleDiscordException($exception);
                }
            }
        }
        return $results;
    }

    /**
     * Remove roles to members
     * @param $guildId
     * @param $usersId
     * @param $rolesId
     */
    public static function removeGuildMembersRoles($guildId, $usersId, $rolesId)
    {
        $results = [];
        foreach ($usersId as $userId) {
            foreach ($rolesId as $roleId) {
                try {
                    app(DiscordClient::class)->guild->removeGuildMemberRole(['guild.id' => $guildId, 'user.id' => $userId, 'role.id' => $roleId]);
                } catch (CommandClientException $exception) {
                    $results[$userId] = self::handleDiscordException($exception);
                }
            }
        }

        return $results;
    }

    /**
     * @param $guildId
     * @param $usersId
     */
    public static function removeGuildMembers($guildId, $usersId)
    {
        $results = [];
        foreach ($usersId as $userId) {
            try {
                app(DiscordClient::class)->guild->removeGuildMember(['guild.id' => intval($guildId), 'user.id' => intval($userId)]);
            } catch (CommandClientException $exception) {
                $results[$userId] = self::handleDiscordException($exception);
            }
        }
        return $results;

    }

    /**
     * @param $guildId
     * @param $usersId
     * @param string $reason
     * @param int $deleteMessageDays
     * @deprecated Problem with Restcord api
     */
    public static function createGuildBans($guildId, $usersId, $reason = "", $deleteMessageDays = 0)
    {
        foreach ($usersId as $userId) {
            app(DiscordClient::class)->guild->createGuildBan(['guild.id' => $guildId, 'user.id' => $userId, 'reason' => $reason, 'delete_message_days' => $deleteMessageDays]);
        }

    }

    /**
     * @param $guildId
     * @param $usersId
     * @deprecated Problem with Restcord api
     */
    public static function removeGuildBans($guildId, $usersId)
    {
        foreach ($usersId as $userId) {
            dd(app(DiscordClient::class)->guild->removeGuildBan(['guild.id' => $guildId, 'user.id' => $userId]));
        }
    }

    public static function listWorkableRoles($guildId)
    {
        $botId = app(DiscordClient::class)->user->getCurrentUser()->id;
        $botRoles = app(DiscordClient::class)->guild->getGuildMember(['guild.id' => $guildId, 'user.id' => $botId])->roles;
        $roles = collect(app(DiscordClient::class)->guild->getGuildRoles(['guild.id' => $guildId]));
        $maxBotRolesPosition = $roles->whereIn('id', $botRoles)->max('position');
        $filteredRoles = $roles->where('managed', false)->whereBetween('position', [0, $maxBotRolesPosition-1]);
        return $filteredRoles->skip(1)->all(); //everyone is ALWAYS the first of the list
    }

    public static function handleDiscordException(CommandClientException $exception)
    {
        dd($exception);
        $code = $exception->getResponse()->getStatusCode();

        $result = [$code => ""];
        switch ($code) {
            case 403:
                $result[$code] = "Vous n'avez pas les permissions de faire cela !";
                break;
            case 401:
                $result[$code] = "Votre session est probablement trop vielle essayez de vous reconnectez.";
                break;
            case 429:
                $result[$code] = "Vous avez trop solicitez l'API discord veuillez resssayez plus tard.";
                break;
            default:
                $result[$code] = "Erreur interne réessayez plus tard ou contactez un administrateur";
        }
        return $result;
    }
}
