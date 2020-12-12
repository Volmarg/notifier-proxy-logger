<?php

namespace App\DTO\API\Internal;

/**
 * @see TranslationAction::getTranslationForIds()
 *
 * Class BaseInternalApiResponseDto
 * @package App\DTO\API\Internal
 */
class GetTranslationsForIdsResponseDto extends BaseInternalApiResponseDto
{
    const KEY_TRANSLATIONS_JSON_FOR_IDS = "translationsJsonForIds";

    public function __construct(string $translationsJsonForIds){
        $this->translationsJsonForIds = $translationsJsonForIds;
    }

    /**
     * @var string $translationsJsonForIds
     */
    private string $translationsJsonForIds = "";

    /**
     * @return string
     */
    public function getTranslationsJsonForIds(): string
    {
        return $this->translationsJsonForIds;
    }

    /**
     * @param string $translationsJsonForIds
     */
    public function setTranslationsJsonForIds(string $translationsJsonForIds): void
    {
        $this->translationsJsonForIds = $translationsJsonForIds;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array                                      = parent::toArray();
        $array[self::KEY_TRANSLATIONS_JSON_FOR_IDS] = $this->getTranslationsJsonForIds();

        return $array;
    }

}