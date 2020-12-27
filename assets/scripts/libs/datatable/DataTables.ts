import {DataTable} from 'simple-datatables';

/**
 * @description handles the logic of searchable/filtered/paginated datatables for vue
 * @link https://www.npmjs.com/package/simple-datatables
 */
export default class DataTables
{

    /**
     * @description will initialize datatables for domElement
     */
    public initForDomElement(domElement: HTMLElement|any){
        new DataTable(domElement, {
            searchable: true,
            fixedHeight: true,
        });
    }
}