/**
 * @description handles the material design text field
 * @link https://material.io/components/text-fields/web#using-text-fields
 */
import {MDCTextField} from "@material/textfield/component";

export default class TextField {

    /**
     * @description will initialize the material design text-field for given dom element
     *
     * @param domElement
     * @return MDCDialog
     */
    public initForDomElement(domElement: HTMLElement|any): MDCTextField
    {
        let textField = new MDCTextField(domElement);
        return textField;
    }


}