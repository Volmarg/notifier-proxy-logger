import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Returns the dto representation from the backed response `getAllEmails`
 */
export default class GetAllDiscordWebhooksResponseDto extends BaseInternalApiResponseDto
{
    private _discordWebhooksJsons = "{}";

    get discordWebhooksJsons(): string {
        return this._discordWebhooksJsons;
    }

    set discordWebhooksJsons(value: string) {
        this._discordWebhooksJsons = value;
    }


    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            discordWebhooksJsons : this.discordWebhooksJsons,
            success              : this.success,
            code                 : this.code,
            message              : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create GetAllEmailsResponseDto from json
     */
    public static fromJson(json: string): GetAllDiscordWebhooksResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for GetAllEmailsResponseDto",
                "exception" : Exception
            }
        }

        let allEmailsResponseDto                  = new GetAllDiscordWebhooksResponseDto();
        allEmailsResponseDto.success              = baseDto.success;
        allEmailsResponseDto.code                 = baseDto.code;
        allEmailsResponseDto.message              = baseDto.message;
        allEmailsResponseDto.discordWebhooksJsons = object.discordWebhooksJsons;

        return allEmailsResponseDto;
    }

    /**
     * @description build GetAllEmailsResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): GetAllDiscordWebhooksResponseDto
    {
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }

}
