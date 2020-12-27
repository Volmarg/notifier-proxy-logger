import StringUtils from "../../core/utils/StringUtils";

import {Placement} from 'tippy.js'
import tippy       from 'tippy.js';

import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';


/**
 * @description this class handles displaying popups upon interacting with DOM elements
 * @link https://atomiks.github.io/tippyjs/
 */
export default class Tippy {

    static readonly DATA_ATTRIBUTE_CONTENT   = "tippyContent";
    static readonly DATA_ATTRIBUTE_PLACEMENT = "tippyPlacement";
    static readonly DEFAULT_PLACEMENT        = "top";

    /**
     * @description will initialize Tippy by the data attr
     *
     * Info: the `any` type is required due to the fact that Vue delivers Html element of type Data
     *       but no such type is defined for TS. Under the hood Data is just a HTMLElement so this is set to support
     *       the native HTMLElement structure and pass/mute `Data` as the `any`
     */
    public applyForElement(domElement: HTMLElement|any): void
    {
        let content   = domElement.dataset[Tippy.DATA_ATTRIBUTE_CONTENT] as string;
        let placement = domElement.dataset[Tippy.DATA_ATTRIBUTE_PLACEMENT] as Placement;

        if( StringUtils.isEmptyString(placement) ){
            placement = Tippy.DEFAULT_PLACEMENT;
        }

        if( !StringUtils.isEmptyString(content) ){
            tippy(domElement, {
                allowHTML : true,
                theme     : 'light',
                content   : content,
                placement : placement
            });
        }
    }

}