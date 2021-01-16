import {MDCDialog} from '@material/dialog';

/**
 * @description handles working with the dialogs
 * @link https://www.npmjs.com/package/@material/dialog
 */
export default class Dialog {

    /**
     * @description will initialize the dialog for given dom element
     * @param domElement
     * @return MDCDialog
     */
    public initForDomElement(domElement: HTMLElement|any): MDCDialog
    {
        let dialog = new MDCDialog(domElement);
        return dialog;
    }

}