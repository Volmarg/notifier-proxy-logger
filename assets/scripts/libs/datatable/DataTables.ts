import {DataTable} from 'simple-datatables';
import Tippy       from '../tippy/Tippy';

/**
 * @description handles the logic of searchable/filtered/paginated datatables for vue
 * @link https://www.npmjs.com/package/simple-datatables
 */
export default class DataTables
{
    /**
     * @type Tippy
     */
    private tippy = new Tippy();

    /**
     * @description will initialize datatables for domElement
     */
    public initForDomElement(domElement: HTMLElement|any): void
    {
        let datatableInstance = new DataTable(domElement, {
            searchable: true,
            fixedHeight: true,
        });

        /*** @description invocation of Tippy is required upon init any kind of data manipulation, it must be done explicitly alongside with DataTables, otherwise it's ignored */
        /*** @link https://github.com/fiduswriter/Simple-DataTables/wiki/Events */
        datatableInstance.on('datatable.init', () => {
            this.attachTippyForDataTableInstance(datatableInstance);
        });
        datatableInstance.on('datatable.page', () => {
            this.attachTippyForDataTableInstance(datatableInstance);
        });
    }

    /***
     * @description will attach tippy logic for DataTable instance rows
     * @param datatableInstance
     * @private
     */
    private attachTippyForDataTableInstance(datatableInstance: DataTable): void
    {
        /*** @description iteration must happen over then childNodes, otherwise the Tippy logic is skipped */
        let rows = datatableInstance.body.childNodes;

        for(let index in rows){
            let row = rows[index];

            /*** @description this is required as for some reason dataTable also stores the function in the child nodes */
            if( "function" === typeof row){
                return;
            }

            this.tippy.applyForElement(row);
        }
    }
}