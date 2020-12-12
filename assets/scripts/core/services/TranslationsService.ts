import Translations                                 from './../../../../translations/frontend/messages.json';
import {VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING}  from "../../env";
import JsonPath                                     from 'jsonpath';

/**
 * @description loads the translation from the translation file
 */
export default class TranslationsService {

    /**
     * @description will return translation file content in form of json
     */
    public getTranslationForString(searchedTranslationString: string): string
    {
        let foundValueArray = JsonPath.query(Translations, searchedTranslationString);
        let foundValue      = foundValueArray[0];

        if( "undefined" === typeof foundValue ){
            return VUE_APP_DEFAULT_STRING_BEFORE_TRANSLATING;
        }

        return foundValue;
    }
}