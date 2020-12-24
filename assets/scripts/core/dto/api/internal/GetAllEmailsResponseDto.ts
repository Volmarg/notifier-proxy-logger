import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Returns the dto representation from the backed response `getAllEmails`
 */
export default class GetAllEmailsResponseDto extends BaseInternalApiResponseDto
{
    private _emailsJsons = "{}";

    get emailsJsons(): string {
        return this._emailsJsons;
    }

    set emailsJsons(value: string) {
        this._emailsJsons = value;
    }


    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            emailsJsons : this.emailsJsons,
            success     : this.success,
            code        : this.code,
            message     : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create GetAllEmailsResponseDto from json
     */
    public static fromJson(json: string): GetAllEmailsResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for LoggedInUserDataDto",
                "exception" : Exception
            }
        }

        let allEmailsResponseDto         = new GetAllEmailsResponseDto();
        allEmailsResponseDto.success     = baseDto.success;
        allEmailsResponseDto.code        = baseDto.code;
        allEmailsResponseDto.message     = baseDto.message;
        allEmailsResponseDto.emailsJsons = object.emailsJsons;

        return allEmailsResponseDto;
    }

    /**
     * @description build GetAllEmailsResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): GetAllEmailsResponseDto
    {
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }

}
