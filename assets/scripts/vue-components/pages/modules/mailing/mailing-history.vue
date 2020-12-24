<!-- Template -->
<template>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ sentEmailsLabel }}  </h2>
          <section class="">
            <VoltTable>
              <VoltTableHead :table-headers="tableHeaders">
              </VoltTableHead>
              <VoltTableBody v-for="mail in tableData">
                <VoltTableRow :row-data="mail"/>
              </VoltTableBody>
            </VoltTable>
          </section>
      </div>
    </div>
  </div>

</template>

<!-- Script -->
<script>
import VoltTable               from '../../../table/volt/table';
import VoltTableHead           from '../../../table/volt/table-head';
import VoltTableBody           from '../../../table/volt/table-body';
import VoltTableRow            from '../../../table/volt/table-row';
import TranslationsService     from "../../../../core/services/TranslationsService";
import SymfonyRoutes           from "../../../../core/symfony/SymfonyRoutes";
import GetAllEmailsResponseDto from "../../../../core/dto/api/internal/GetAllEmailsResponseDto";
import MailDto                 from "../../../../core/dto/modules/mailing/MailDto";

let translationService = new TranslationsService();

export default {
  components: {
    VoltTableBody,
    VoltTableHead,
    VoltTableRow,
    VoltTable
  },
  beforeMount(){
    this.retrieveAllEmails();
  },
  data(){
    return {
      allEmails: [],
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
          let mailDto = (new MailDto()).fromJson(json);
          allEmailsDtos.push(mailDto)
        }

        this.allMails  = allEmailsDtos;
        this.tableData = allEmailsDtos;
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
        let filteredTableData = [];
        this.allEmails.forEach( (value, index) => {
          value.body = value.body.substr(0, 20) + '...';
          filteredTableData.push(value);
        })

        return filteredTableData;
      },
    }
  }
}
</script>