/**
 * @description This class contains definitions of INTERNAL api routes defined on backend side
 *              there is no way to pass this via templates etc so whenever a route is being changed in the symfony
 *              it also has to be updated here.
 *
 *              This solution was added to avoid for example calling translation api and having string hardcoded in
 *              all the places.
 */
export default class SymfonyRoutes {

    static readonly GET_LOGGED_IN_USER_DATA = "/api-internal/get-logged-in-user-data";
    static readonly SEND_TEST_MAIL          = "/modules/mailing/send-test-mail";

    static readonly GET_SCR_TOKEN_PARAM_FORM_NAME = "{formName}";
    static readonly GET_CSRF_TOKEN                = "/api-internal/get-csrf-token/{formName}";

    /**
     * @description will use the url with params and replace the params with values
     */
    public static buildUrlWithReplacedParams(processedUrl: string, replacedParamsWithValues: object): string
    {
        let keys = Object.keys(replacedParamsWithValues);
        keys.forEach( (key, index) => {
            let value = replacedParamsWithValues[key];
            processedUrl = processedUrl.replace(key, value);
        })

        return processedUrl;
    }

}