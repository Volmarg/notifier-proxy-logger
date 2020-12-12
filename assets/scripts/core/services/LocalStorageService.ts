import StringUtils from "../utils/StringUtils";
import LoggedInUserDataDto from "../dto/api/internal/LoggedInUserDataDto";

export default class LocalStorageService {

    static readonly SESSION_KEY_TRANSLATIONS = "translations";
    static readonly SESSION_KEY_LOGGED_USER  = "loggedUser";

    /**
     * @description will set the logged in user dto in local storage
     */
    public setLoggedInUser(loggedInUserDataDto: LoggedInUserDataDto): void
    {
        localStorage.setItem(LocalStorageService.SESSION_KEY_LOGGED_USER, loggedInUserDataDto.toJson());
    }

    /**
     * @description will get the logged in user dto from local storage or null if it's not set
     */
    public getLoggedInUser(): LoggedInUserDataDto | null
    {
        let json = localStorage.getItem(LocalStorageService.SESSION_KEY_LOGGED_USER);

        if( !StringUtils.isEmptyString(json) ){
            let dto = LoggedInUserDataDto.fromJson(json);
            return dto;
        }

        return null;
    }

    /**
     * @description will check if the logged in user data is set in local storage
     */
    public isLoggedInUserSet(): boolean
    {
        let json = localStorage.getItem(LocalStorageService.SESSION_KEY_LOGGED_USER);
        return !StringUtils.isEmptyString(json)
    }

    /**
     * @description will return translation string from local storage if such is saved or empty string if nothing was found
     */
    public getTranslationForString(searchedString: string): string
    {
        let allTranslations = this.getAllTranslations();
        if( null === allTranslations ){
            return "";
        }

        let foundValue = allTranslations[searchedString];
        if( StringUtils.isEmptyString(foundValue) ){
            return "";
        }

        return foundValue;
    }

    /**
     * @description will save translation string in local storage
     */
    public setTranslationForString(key: string, value: string): void
    {
        let allTranslations = this.getAllTranslations();
        if( null === allTranslations ){
            allTranslations = {};
        }

        allTranslations     = { ...allTranslations, ...{
            [key]: value
        } }

        localStorage.setItem(LocalStorageService.SESSION_KEY_TRANSLATIONS, JSON.stringify(allTranslations));
    }

    /**
     * @description will check if the translation string is set
     */
    public isTranslationForString(key: string): boolean
    {
        let tranlsation = this.getTranslationForString(key);
        return !StringUtils.isEmptyString(tranlsation);
    }

    /**
     * @description will check if all of the string are present in translation object
     */
    public areTranslationsForStrings(keys: Array<string>): boolean
    {
        keys.forEach( (key) => {
            let translation = this.getTranslationForString(key);
            return !StringUtils.isEmptyString(translation);
        });

        return true;
    }

    /**
     * @description will return all translations from local session or empty object
     */
    private getAllTranslations(): object | null
    {
        let allTranslationsString = localStorage.getItem(LocalStorageService.SESSION_KEY_TRANSLATIONS);
        if( "undefined" === typeof allTranslationsString ){
            return null;
        }

        let allTranslationsObject = JSON.parse(allTranslationsString);
        return allTranslationsObject;
    }
}