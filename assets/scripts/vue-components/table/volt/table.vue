<!-- Template -->
<template>
  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-end">

      <div class="d-inline-block align-self-center">
        <label for="searchInput" class="m-0">{{ searchTranslationString }}</label>
      </div>

      <div class="col-2 d-inline-block ml-2">
        <input type="text" v-model="searchInput" id="searchInput" class="form-control col-2" @change="searchForStringInTableCells()"/>
      </div>

    </div>
  </div>

  <table class="table table-centered table-nowrap mb-0 rounded" ref="table">
    <volt-table-head :table-headers="headers">
    </volt-table-head>
    <volt-table-body v-if="shownRowsData.length">
      <volt-table-row
          v-for="(rowData, index) in shownRowsData"
          :key="index"
          :row-data="rowData"
          :tippy-row-body-content="getTippyBodyContentForSingleRow(index)"
      />
    </volt-table-body>
  </table>

  <template
      v-for="pageNumber in paginationCount"
      :key="pageNumber"
  >
    <button
        v-show="
            isCurrentlyRenderedPageNumberShown(pageNumber)
        || (pageNumber == nextResultPage)
        || (pageNumber == previousResultPage)
        || (pageNumber == currentResultPage)
        "
        class="btn btn-light ml-1 mt-1 pagination-button"
        :class="{
        'text-danger': (pageNumber == currentResultPage)
      }"
        @click="handleShowingTableDataForPaginationAndResult($event.currentTarget.innerHTML)"
        :ref="'pageNumberButton' + pageNumber"
    >
      {{ pageNumber }}
    </button>
    <span v-if="doShowPaginationDots(pageNumber)">
      ...
    </span>
  </template>

</template>

<!-- Script -->
<script>
import TranslationsService    from '../../../core/services/TranslationsService';
import StringUtils            from "../../../core/utils/StringUtils";

import VoltTableHeadComponent from "./table-head";
import VoltTableRowComponent  from "./table-row";
import VoltTableBodyComponent from "./table-body";

let translationsService = new TranslationsService();

export default {
  data(){
    return {
      datatableInstance : null,
      originalRowsData  : [],
      shownRowsData     : [],

      maxResultPerPage               : 10,
      currentResultPage              : 1,
      paginationCount                : 0,
      countOfPagesShownInBetweenDots : 3,
      arePaginationDotsRendered      : false,

      nextResultPage     : this.currentResultPage+1,
      previousResultPage : this.currentResultPage-1,

      searchInput      : "",
      searchResultRows : [],
    }
  },
  computed: {
    searchTranslationString: function(){
      return translationsService.getTranslationForString('mainPageComponents.table.searchInput.label')
    }
  },
  props: {
    "headers": {
      type     : Array,
      required : true,
    },
    "rowsData": {
      type     : Array,
      required : true,
    },
    "tippyContentForAllRowsData": {
      type     : Array,
      required : false,
      default  : []
    }
  },
  components: {
    'volt-table-head' : VoltTableHeadComponent,
    'volt-table-row'  : VoltTableRowComponent,
    'volt-table-body' : VoltTableBodyComponent,
  },
  methods: {
    /**
     * @description will decide if given page number should be visible upon rendering
     */
    isCurrentlyRenderedPageNumberShown(pageNumber){

      // show the basic numbers from beginning and end
      if(
              pageNumber <= this.countOfPagesShownInBetweenDots
          ||  pageNumber > ( this.paginationCount - this.countOfPagesShownInBetweenDots )
      ){
        return true;
      }

      return false;
    },
    /**
     * @description will decide if the dots for pagination should be shown
     */
    doShowPaginationDots(pageNumber){
      // don't add dots if there are only few pages
      if(this.paginationCount <= this.countOfPagesShownInBetweenDots ){
        return;
      }

      let centerOfPagination = ( this.paginationCount / 2 );

      return ( parseInt(pageNumber) === Math.ceil(centerOfPagination) );
    },
    /**
     * @description this function will decide if we need to show some additional pagination buttons in between
     *              the numbers that are shown initially. Like for example when user clicks on 3 - we want to show 4,
     *              but when it's 4 and so on we show another button 5 after dots etc.
     */
    decideShowingPaginationButtonsInBetweenLowerAndHigherRanges(){
      this.nextResultPage     = this.currentResultPage + 1;
      this.previousResultPage = this.currentResultPage - 1;
    },
    /**
     * @description creates tippy content for single data row - this means the popup visible upon hovering
     */
    getTippyBodyContentForSingleRow(index){
      if( index in this.tippyContentForAllRowsData ){
        return this.tippyContentForAllRowsData[index];
      }

      return "";
    },
    /**
     * @description handles showing the rows in table based on searched string in input field
     *              the search looks if any of the property provided as rowData has given string
     */
    searchForStringInTableCells(){
      let matchingRowsData = [];
      let searchRegexp     = new RegExp(this.searchInput, 'i');

      if( "" === this.searchInput.trim() ){
        this.handleShowingTableDataForPaginationAndResult(this.currentResultPage);
        this.updatePaginationCount();
        return;
      }

      this.originalRowsData.forEach( (objectWithData) => {
        let objectValues = Object.values(objectWithData);

        // iterate over all props of object
        for( let value of objectValues ){

          // object is a value of prop
          if( "object" === typeof value ){

            for(let innerObjectValue of value){
              if( innerObjectValue.match(searchRegexp) ){
                matchingRowsData.push(objectWithData);
                return false;
              }
            }
            //string is a value of prop
          }else{

            if( value.match(searchRegexp) ){
              matchingRowsData.push(objectWithData);
              return false;
            }
          }
        }
      })

      this.shownRowsData    = matchingRowsData;
      this.searchResultRows = matchingRowsData;

      this.handleShowingTableDataForPaginationAndResult(1);
      this.updatePaginationCount();
    },
    /**
     * @description handles setting the pagination based on the total data in table or based on active search result
     */
    updatePaginationCount(){
      if( "" === this.searchInput ){
        this.paginationCount = Math.ceil(this.originalRowsData.length / this.maxResultPerPage);
      }else{
        this.paginationCount = Math.ceil(this.searchResultRows.length / this.maxResultPerPage);
      }
    },
    /**
     * @description handles
     *             - showing the rows in table for current pageNumber by slicing the data in array
     *             - rendering pagination based on the page which user currently is on
     *
     * @param visiblePageNumber
     */
    handleShowingTableDataForPaginationAndResult(visiblePageNumber){
      /** @description this is required as on page 1 we want offset starting from 1, page 2 from 11 etc **/
      let visiblePageNumberOffsetMultiplier = (visiblePageNumber - 1);

      let startOffset = 0;
      if(1 != visiblePageNumber){
        startOffset = (visiblePageNumberOffsetMultiplier * this.maxResultPerPage) + 1
      }
      let endOffset = startOffset + this.maxResultPerPage;

      if( StringUtils.isEmptyString(this.searchInput) ){
        this.shownRowsData = this.originalRowsData.slice(startOffset, endOffset)
      }else{
        this.shownRowsData = this.searchResultRows.slice(startOffset, endOffset)
      }

      this.currentResultPage = parseInt(visiblePageNumber);
      this.decideShowingPaginationButtonsInBetweenLowerAndHigherRanges();
    }
  },
  /**
   * @description mostly setting the initial values
   */
  updated() {
    // deny working with the table if there is not data present to display
    if( !this.rowsData.length ){
      return;
    }

    // store original rows data for future filtering etc
    if (0 === this.searchResultRows.length) {
      this.originalRowsData = this.rowsData;
      this.searchResultRows = this.rowsData; // initial data is required
    }

    if (
            0 === this.shownRowsData.length
        &&  StringUtils.isEmptyString(this.searchInput)
    ) {
      this.shownRowsData = this.rowsData;
      this.handleShowingTableDataForPaginationAndResult(1); // show initial data for page 1
    }

    // set initial pagination count when opening page
    if (0 === this.paginationCount) {
      this.updatePaginationCount();
    }

    /**
     * @description original result is a data used for further manipulation
     *              it's required to change it upon passing new value of `rowsData` (prop)
     */
    if( this.originalRowsData.length !== this.rowsData.length ){
      this.originalRowsData = this.rowsData;
      this.handleShowingTableDataForPaginationAndResult(this.currentResultPage);
      this.updatePaginationCount();
    }
  }
}

</script>

<!-- Style -->
<style scoped>
.pagination-button{
  width: 37px;
  padding: 5px;
  margin: 3px;
}
</style>