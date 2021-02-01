/**
 * @description contains utility logic to work with objects
 */
export default class ObjectUtils {

    /**
     * @description will clone the object, the deep clone means that the `link` to original object is severed
     */
    public static deepCloneObject(object: Object): Object
    {
        let clonedObject = JSON.parse(JSON.stringify(object));
        return clonedObject;
    }

}