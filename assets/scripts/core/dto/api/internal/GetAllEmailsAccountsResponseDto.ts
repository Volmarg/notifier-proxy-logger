import BaseInternalApiResponseDto from "./BaseInternalApiResponseDto";

/**
 * @description Returns the dto representation from the backed response `getAllMailsAccount`
 */
export default class GetAllEmailsAccountsResponseDto extends BaseInternalApiResponseDto
{
    private _emailsAccountsJsons: Array<string> = [];

    get emailsAccountsJsons(): Array<string> {
        return this._emailsAccountsJsons;
    }

    set emailsAccountsJsons(value: Array<string>) {
        this._emailsAccountsJsons = value;
    }

    /**
     * @description returns current dto as string
     */
    public toJson(): string
    {
        let object = {
            emailsJsons : this.emailsAccountsJsons,
            success     : this.success,
            code        : this.code,
            message     : this.message
        }

        return JSON.stringify(object);
    }

    /**
     * @description Create GetAllEmailsAccountsResponseDto from json
     */
    public static fromJson(json: string): GetAllEmailsAccountsResponseDto
    {
        let baseDto = super.fromJson(json);

        try{
            var object = JSON.parse(json);
        }catch(Exception){
            throw{
                "message"   : "Could not parse json to object for GetAllEmailsAccountsResponseDto",
                "exception" : Exception
            }
        }

        let allEmailsResponseDto                 = new GetAllEmailsAccountsResponseDto();
        allEmailsResponseDto.success             = baseDto.success;
        allEmailsResponseDto.code                = baseDto.code;
        allEmailsResponseDto.message             = baseDto.message;
        allEmailsResponseDto.emailsAccountsJsons = object.emailsAccountsJsons;

        return allEmailsResponseDto;
    }

    /**
     * @description build GetAllEmailsAccountsResponseDto from axios response object
     */
    public static fromAxiosResponse(axiosResponse: object): GetAllEmailsAccountsResponseDto
    {
        let json = JSON.stringify(axiosResponse.data);
        let dto  = this.fromJson(json);

        return dto;
    }

}
