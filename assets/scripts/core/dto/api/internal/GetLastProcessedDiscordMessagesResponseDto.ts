import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Returns the dto representation from the backed response `getLastProcessedDiscordMessages`
 */
export default class GetLastProcessedDiscordMessagesResponseDto extends BaseInternalApiResponseDto
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
            emailsJsons : this.discordMessagesJsons,
            success     : this.success,
            code        : this.code,
            message     : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create GetLastProcessedEmailsResponseDto from json
     */
    public static fromJson(json: string): GetLastProcessedDiscordMessagesResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for GetLastProcessedDiscordMessagesResponseDto",
                "exception" : Exception
            }
        }

        let allEmailsResponseDto                  = new GetLastProcessedDiscordMessagesResponseDto();
        allEmailsResponseDto.success              = baseDto.success;
        allEmailsResponseDto.code                 = baseDto.code;
        allEmailsResponseDto.message              = baseDto.message;
        allEmailsResponseDto.discordMessagesJsons = object.discordMessagesJsons;

        return allEmailsResponseDto;
    }

    /**
     * @description build GetLastProcessedDiscordMessagesResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): GetLastProcessedDiscordMessagesResponseDto
    {
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }

}