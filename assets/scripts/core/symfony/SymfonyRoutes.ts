/**
 * @description This class contains definitions of INTERNAL api routes defined on backend side
 *              there is no way to pass this via templates etc so whenever a route is being changed in the symfony
 *              it also has to be updated here.
 *
 *              This solution was added to avoid for example calling translation api and having string hardcoded in
 *              all the places.
 */
export default class SymfonyRoutes {

    /**
     * @description returns the data of currently logged in user
     */
    static readonly GET_LOGGED_IN_USER_DATA = "/api-internal/get-logged-in-user-data";

    /**
     * @description will send test email
     */
    static readonly SEND_TEST_MAIL = "/modules/mailing/send-test-mail";

    /**
     * @description returns the CSRF token - required by Symfony when the CSRF validation is turned on
     */
    static readonly GET_CSRF_TOKEN_PARAM_FORM_NAME = "{formName}";
    static readonly GET_CSRF_TOKEN                 = "/api-internal/get-csrf-token/{formName}";

    /**
     * @description returns all Emails entities data
     */
    static readonly GET_ALL_EMAILS = "/modules/mailing/get-all-emails";

    /**
     * @description returns last processed emails
     *              used for example in the dashboard widget
     */
    static readonly GET_LAST_PROCESSED_EMAILS                    = "/modules/dashboard/get-last-processed-emails/{emailsCount}"
    static readonly GET_LAST_PROCESSED_EMAILS_PARAM_EMAILS_COUNT = "{emailsCount}";

    /**
     * @description returns all defined webhooks used for sending messages in discord
     */
    static readonly GET_ALL_DISCORD_WEBHOOKS = "/modules/discord/get-all-webhooks";

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