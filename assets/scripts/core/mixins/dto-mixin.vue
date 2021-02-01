<script>
import ObjectUtils from "../utils/ObjectUtils";

/**
 * @description common logic used for working with dto
 */
export default {
  methods: {
    /**
     * @description will return dto for given entity id
     *
     * @param entityId  String
     * @param dataArray Array
     * @param dtoObjectClass
     * @return Boolean
     */
    hasDtoEntityWithId(entityId, dataArray, dtoObjectClass){
      return !(null === this.getDtoForEntityWithId(entityId, dataArray, dtoObjectClass));
    },
    /**
     * @description will return dto for given entity id, or null if nothing is found
     *
     * @param entityId       String
     * @param dataArray      Array
     * @param dtoObjectClass String
     * @return Object
     */
    getDtoForEntityWithId(entityId, dataArray, dtoObjectClass){
      let foundEntities = dataArray.filter( (discordDto) => {
        return (discordDto.id === entityId);
      }, dataArray)

      if(0 === foundEntities.length){
        return null;
      }

      let foundEntity = foundEntities[0];

      /**
       * @description first create plain object, then create object of given classname
       */
      let clonedDto   = ObjectUtils.deepCloneObject(foundEntity);
      let classDto    = Object.assign(new dtoObjectClass, clonedDto);

      return classDto;
    },
    /**
     * @description Will return the index of element in the dtos array, for given entityId
     *              null is returned if nothing is found
     *
     * @param entityId String
     * @param dataArray Array
     * @return ?Number
     */
    getDtoIndexForEntityWithId(entityId, dataArray){
      let indexOfDto    = null
      let indexesOfDtos = Object.keys(dataArray);

      for(let index of indexesOfDtos){
        let dto = dataArray[index];
        if(entityId == dto.id){
          indexOfDto = index;
          break;
        }
      }
      return indexOfDto;
    },
  }
}
</script>
