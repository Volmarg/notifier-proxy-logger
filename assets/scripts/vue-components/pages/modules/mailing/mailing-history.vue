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
                  <volt-table-row :row-data="mail"/>
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
import StringUtils             from "../../../../core/utils/StringUtils";
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
  beforeMount(){
    this.retrieveAllEmails();
  },
  data(){
    return {
      allEmails        : [],
      isSpinnerVisible : true,
    }
  },
  methods: {
    retrieveAllEmails(){
      this.axios.get(SymfonyRoutes.GET_ALL_EMAILS).then( (response) => {
        let allEmailsResponseDtp = GetAllEmailsResponseDto.fromAxiosResponse(response);
        let allEmailsDtos        = [];
        let emailsJsons          = allEmailsResponseDtp.emailsJsons;

        for(let index in emailsJsons){
          let json    = emailsJsons[index];
          let mailDto = MailDto.fromJson(json);
          allEmailsDtos.push(mailDto)
        }

        this.allMails         = allEmailsDtos;
        this.isSpinnerVisible = false;
      })
    }
  },
  computed: {
    tableHeaders: {
      get: function () {
        return translationService.getTranslationsForStrings([
            'pages.mailing.history.table.headers.subject.label',
            'pages.mailing.history.table.headers.body.label',
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
    tableData: {
      get: function(){
        let filteredTableData      = [];
        let bodyMaxCharactersCount = 20;

        this.allEmails.forEach( (value, index) => {
          value.body = StringUtils.substringAndAddDots(value.body, bodyMaxCharactersCount);
          filteredTableData.push(value);
        })

        return filteredTableData;
      },
    }
  }
}
</script>