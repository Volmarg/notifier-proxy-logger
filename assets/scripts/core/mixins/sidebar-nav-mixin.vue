<script>
/**
 * @description common logic for navigation handling
 */
export default {
  data(){
    return {
      isParentNodeActive: false
    }
  },
  computed: {
    nodesNames(){
      return [];
    }
  },
  methods: {
    /**
     * @description will check if the parent node is active or not -- mixin
     *
     */
    handleParentNodeActive(route){
      this.isParentNodeActive = this.nodesNames.includes(route.name);

      if(this.isParentNodeActive){
        this.$refs.parentNodeArrowSpan.attributes['aria-expanded'].nodeValue = true;
        this.$refs.parentNodeArrowSpan.classList.remove('collapsed');
        this.$refs.childNodesListWrapper.classList.add('show');
      }else{
        this.$refs.parentNodeArrowSpan.attributes['aria-expanded'].nodeValue = false;
        this.$refs.parentNodeArrowSpan.classList.add('collapsed');
        this.$refs.childNodesListWrapper.classList.remove('show');
      }

    },
    /**
     * @description will check if the component which implements this mixin is fully prepared to use it
     */
    _checkRequirementsForMixin(){
      if("undefined" === typeof this.$refs.parentNodeArrowSpan){
        throw{
          "mixin"   : "sidebar-nav-mixin",
          "message" : "Ref element was not defined: parentNodeArrowSpan"
        }
      }

      if("undefined" === typeof this.$refs.parentNodeArrowSpan){
        throw{
          "mixin"   : "sidebar-nav-mixin",
          "message" : "Ref element was not defined: parentNodeArrowSpan"
        }
      }

      if(0 === this.nodesNames.length){
        throw{
          "mixin"   : "sidebar-nav-mixin",
          "message" : "Nodes names array is empty"
        }
      }
    }
  },
  updated(){
    this._checkRequirementsForMixin();
    this.handleParentNodeActive(this.$route)
  }
}
</script>