<?php


namespace App\DTO\API\Internal;


use App\DTO\API\BaseApiResponseDto;
use App\DTO\Modules\Discord\DiscordWebhookDto;

class GetAllDiscordWebhooksResponseDto extends BaseApiResponseDto
{

    const KEY_WEBHOOKS_DTO_JSONS = "discordWebhooksJsons";

    /**
     * @var DiscordWebhookDto[] $webhooksDto
     */
    private array $webhooksDto = [];

    /**
     * @return DiscordWebhookDto[]
     */
    public function getWebhooksDto(): array
    {
        return $this->webhooksDto;
    }

    /**
     * @param array $webhooksDto
     */
    public function setWebhooksDto(array $webhooksDto): void
    {
        $this->webhooksDto = $webhooksDto;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dtoJsons = [];
        foreach( $this->getWebhooksDto() as $dto ){
            $dtoJsons[] = $dto->toJson();
        }

        $array                               = parent::toArray();
        $array[self::KEY_WEBHOOKS_DTO_JSONS] = $dtoJsons;

        return $array;
    }
}