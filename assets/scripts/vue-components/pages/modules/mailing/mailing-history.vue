<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ sentEmailsLabel }}  </h2>

        <section class="">

          <volt-table
              :headers="tableHeaders"
              :rows-data="tableData"
              @pagination-button-clicked="onPaginationButtonClickedHandler"
              @handle-showing-table-data-for-pagination-and-result="handleDataForPaginationAndSearch"
              @search-for-string-in-table-cells="searchForStringInTableCells"
              ref="table"
          >
            <volt-table-row
                v-for="(mailDto, index) in currentlyVisibleDataInTable"
                :key="index"
                :row-data="mailDto"
                :tippy-row-body-content="buildRowTippyBodyContentForMail(allEmails[index])"
                :skipped-keys="skippedDtoProperties"
            />
          </volt-table>

        </section>

      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import VoltTable                 from '../../../table/volt/table';
import VoltTableHead             from '../../../table/volt/table-head';
import VoltTableBody             from '../../../table/volt/table-body';
import VoltTableRow              from '../../../table/volt/table-row';
import SemipolarSpinnerComponent from '../../../../vue-components/libs/epic-spinners/semipolar-spinner';

import TranslationsService     from "../../../../core/services/TranslationsService";
import SymfonyRoutes           from "../../../../core/symfony/SymfonyRoutes";
import GetAllEmailsResponseDto from "../../../../core/dto/api/internal/GetAllEmailsResponseDto";
import MailDto                 from "../../../../core/dto/modules/mailing/MailDto";

let translationService = new TranslationsService();

export default {
  components: {
    "volt-table-body"   : VoltTableBody,
    "volt-table-head"   : VoltTableHead,
    "volt-table-row"    : VoltTableRow,
    "volt-table"        : VoltTable,
    "semipolar-spinner" : SemipolarSpinnerComponent
  },
  created(){
    this.retrieveAllEmails();
  },
  data(){
    return {
      allEmails                   : [],
      tableData                   : [],
      currentlyVisibleDataInTable : [],
      isSpinnerVisible            : true,
    }
  },
  computed: {
    tableHeaders: {
      get: function () {
        return translationService.getTranslationsForStrings([
          'pages.mailing.history.table.headers.subject.label',
          'pages.mailing.history.table.headers.status.label',
          'pages.mailing.history.table.headers.created.label',
          'pages.mailing.history.table.headers.receivers.label'
        ]);
      },
    },
    sentEmailsLabel: {
      get: function () {
        return translationService.getTranslationForString('pages.mailing.history.labels.main');
      }
    },
    allMails: {
      get: function() {
        return this.allEmails;
      },
      set: function (emails) {
        this.allEmails = emails;
      }
    },
    tippyBodyContentTranslationBodyString: function(){
      return translationService.getTranslationForString('pages.dashboard.overview.widgets.lastProcessedEmails.tippy.bodyContent.content');
    },
    skippedDtoProperties(){
      return [
        "body",
      ];
    }
  },
  methods: {
    /**
     * @description retrieve all Mails for further processing like for example display in table
     */
    retrieveAllEmails(){
      this.axios.get(SymfonyRoutes.GET_ALL_EMAILS).then( (response) => {
        let allEmailsResponseDtos = GetAllEmailsResponseDto.fromAxiosResponse(response);
        let emailsJsons           = allEmailsResponseDtos.emailsJsons;

        this.allMails = emailsJsons.map( (json) => {
          return MailDto.fromJson(json);
        });

        let tableDataDtos = emailsJsons.map( (json) => {
          return MailDto.fromJson(json);
        });

        this.currentlyVisibleDataInTable = this.processMailsDataForDisplayingInTable(tableDataDtos);
        this.tableData                   = this.processMailsDataForDisplayingInTable(tableDataDtos);
        this.isSpinnerVisible            = false;
      })
    },
    /**
     * @description build the content for Tippy.js - visible upon hovering over the row in history table
     *
     * @param mailDto {MailDto}
     */
    buildRowTippyBodyContentForMail(mailDto){

      return `
          <b>${this.tippyBodyContentTranslationBodyString}:</b>
          <br/>
          ${mailDto.body}
        `;
    },
    /**
     * @description will filter the data for displaying in table, either set empty value to skip them or add special
     *              formatting/styling
     *
     * @param mailsDtos {Array<MailDto>}
     * @returns {Array<MailDto>}
     */
    processMailsDataForDisplayingInTable(mailsDtos){
        let filteredTableData      = [];

        mailsDtos.forEach( (mail, index) => {

          let classes = "";
          switch(mail.status){

            case MailDto.STATUS_PENDING:
              classes+= " npl-text-color-dark-orange";
            break;

            case MailDto.STATUS_SENT:
              classes+= " text-success";
            break;

            default:
              classes+= " text-danger";
            break;
          }

          mail.status = `<b class="${classes}">${mail.status}</b>`;

          filteredTableData.push(mail);
        })

        return filteredTableData;
      },
      /**
       * @description method triggered when the pagination button in table was clicked
       *
       * @param clickedPageNumber
       */
      onPaginationButtonClickedHandler(clickedPageNumber){
        let tableComponent               = this.$refs.table;
        tableComponent.currentResultPage = clickedPageNumber;
        tableComponent.handleShowingTableDataForPaginationAndResult(clickedPageNumber);
      },
      /**
       * @description method triggered when the table data is being filtered by the `pagination logic`
       *
       * @param shownResult
       */
      handleDataForPaginationAndSearch(shownResult){
        this.currentlyVisibleDataInTable = shownResult;
      },
      /**
       * @description method triggered when some data is changing in the search input for table
       *
       * @param searchResult
       */
      searchForStringInTableCells(searchResult){
        this.currentlyVisibleDataInTable = searchResult;
      }
  }
}
</script>