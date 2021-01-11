<?php


namespace App\DTO\API\Internal;


use App\DTO\API\BaseApiResponseDto;

class GetAllDiscordMessagesResponseDto extends BaseApiResponseDto
{

    const KEY_DISCORD_MESSAGES_JSONS = "discordMessagesJsons";

    private array $discordMessagesJsons = [];

    /**
     * @return array
     */
    public function getDiscordMessagesJsons(): array
    {
        return $this->discordMessagesJsons;
    }

    /**
     * @param array $discordMessagesJsons
     */
    public function setDiscordMessagesJsons(array $discordMessagesJsons): void
    {
        $this->discordMessagesJsons = $discordMessagesJsons;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                                   = parent::toArray();
        $array[self::KEY_DISCORD_MESSAGES_JSONS] = $this->getDiscordMessagesJsons();

        return $array;
    }
}