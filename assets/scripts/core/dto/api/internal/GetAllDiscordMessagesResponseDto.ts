import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Returns the dto representation from the backed response `getAllDiscordMessages`
 */
export default class GetAllDiscordMessagesResponseDto extends BaseInternalApiResponseDto
{
    private _discordMessagesJsons = "{}";

    get discordMessagesJsons(): string {
        return this._discordMessagesJsons;
    }

    set discordMessagesJsons(value: string) {
        this._discordMessagesJsons = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            discordMessagesJsons : this.discordMessagesJsons,
            success              : this.success,
            code                 : this.code,
            message              : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create GetAllDiscordMessagesResponseDto from json
     */
    public static fromJson(json: string): GetAllDiscordMessagesResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for GetAllDiscordMessagesResponseDto",
                "exception" : Exception
            }
        }

        let allDiscordMessagesResponseDto                  = new GetAllDiscordMessagesResponseDto();
        allDiscordMessagesResponseDto.success              = baseDto.success;
        allDiscordMessagesResponseDto.code                 = baseDto.code;
        allDiscordMessagesResponseDto.message              = baseDto.message;
        allDiscordMessagesResponseDto.discordMessagesJsons = object.discordMessagesJsons;

        return allDiscordMessagesResponseDto;
    }

    /**
     * @description build GetAllDiscordMessagesResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): GetAllDiscordMessagesResponseDto
    {
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }

}
