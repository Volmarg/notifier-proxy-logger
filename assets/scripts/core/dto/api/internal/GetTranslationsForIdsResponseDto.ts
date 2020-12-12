import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Used to fetch the translations from the backend
 */
export default class GetTranslationsForIdsResponseDto extends BaseInternalApiResponseDto{

    private _translationsJsonForIds: object;

    get translationsJsonForIds(): object {
        return this._translationsJsonForIds;
    }

    set translationsJsonForIds(value: string) {
        this._translationsJsonForIds = JSON.parse(value);
    }

    /**
     * @description will create object from the json (delivered from response)
     */
    public static fromJson(json: string): GetTranslationsForIdsResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse the json for: GetTranslationsForIdsResponseDto",
                "exception" : Exception
            }
        }

        let dto = new GetTranslationsForIdsResponseDto();
        dto.code                   = baseDto.code;
        dto.message                = baseDto.message;
        dto.success                = baseDto.success;
        dto.translationsJsonForIds = object.translationsJsonForIds;

        return dto;
    }

    /**
     * @description builds GetTranslationsForIdsResponseDto from axios response
     */
    public static fromAxiosResponse(response: object): GetTranslationsForIdsResponseDto
    {
        let json = JSON.stringify(response.data);
        let dto  = GetTranslationsForIdsResponseDto.fromJson(json);

        return dto;
    }
}