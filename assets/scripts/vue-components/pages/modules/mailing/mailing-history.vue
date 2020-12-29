<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ sentEmailsLabel }}  </h2>
          <section class="">
            <volt-table>
              <volt-table-head :table-headers="tableHeaders">
              </volt-table-head>
              <volt-table-body v-if="tableData.length">
                <template v-for="(mail, index) in tableData" :key="index">
                  <volt-table-row :row-data="mail" :tippy-row-body-content="buildRowTippyBodyContentForMail(allEmails[index])"/>
                </template>
              </volt-table-body>
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
      allEmails        : [],
      tableData        : [],
      isSpinnerVisible : true,
    }
  },
  methods: {
    /**
     * @description retrieve all Mails for further processing like for example display in table
     */
    retrieveAllEmails(){
      this.axios.get(SymfonyRoutes.GET_ALL_EMAILS).then( (response) => {
        let allEmailsResponseDtos = GetAllEmailsResponseDto.fromAxiosResponse(response);
        let allEmailsDtos         = [];
        let tableDataDtos         = [];
        let emailsJsons           = allEmailsResponseDtos.emailsJsons;

        for(let index in emailsJsons){
          let json = emailsJsons[index];

          /** @description duplication required due to overwriting original object upon changing it's value */
          let mailDtoForAllEmails = MailDto.fromJson(json);
          let mailDtoForTableData = MailDto.fromJson(json);

          allEmailsDtos.push(mailDtoForAllEmails)
          tableDataDtos.push(mailDtoForTableData)
        }

        this.allMails  = allEmailsDtos;
        this.tableData = this.filterMailsDataForDisplayingInTable(tableDataDtos);
        this.isSpinnerVisible = false;
      })
    },
    /**
     * @description build the content for Tippy.js - visible upon hovering over the row in history table
     *
     * @param mail {MailDto}
     */
    buildRowTippyBodyContentForMail(mail){
      let content = `
        <b>${this.tippyBodyContentTranslationBodyString}:</b>
        <br/>
        ${mail.body}
      `;
      return content;
    },
    /**
     * @description will filter the data for displaying in table, either set empty value to skip them or add special
     *              formatting/styling
     *
     * @param mailsDtos {Array<MailDto>}
     * @returns {Array<MailDto>}
     */
    filterMailsDataForDisplayingInTable(mailsDtos){
        let filteredTableData      = [];

        mailsDtos.forEach( (mail, index) => {

          /** @description these columns have to be skipped */
          mail.body = "";

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
  }
}
</script>