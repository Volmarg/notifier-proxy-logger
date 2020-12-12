/**
 * @description This class contains definitions of INTERNAL api routes defined on backend side
 *              there is no way to pass this via templates etc so whenever a route is being changed in the symfony
 *              it also has to be updated here.
 *
 *              This solution was added to avoid for example calling translation api and having string hardcoded in
 *              all the places.
 */
export default class SymfonyRoutes {

    // Translations for ids
    static readonly KEY_NAME_TRANSLATIONS_IDS = "translationsIds";
    static readonly GET_TRANSLATIONS_FOR_IDS  = "/api-internal/get-translations-for-ids";

    // Logged in user data
    static readonly GET_LOGGED_IN_USER_DATA = "/api-internal/get-logged-in-user-data";

}