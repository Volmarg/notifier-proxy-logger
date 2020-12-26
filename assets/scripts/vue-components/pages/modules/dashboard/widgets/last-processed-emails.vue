<!-- Template -->
<template>
  <card-with-title :card-title="widgetTitleTranslation" :is-spinner-visible="isSpinnerVisible">
    <template #card-body>
      <div v-if="[lastProcessedEmails.length]">

        <!-- todo: check why the if required [], and the ones below don't -->
        <row-fontawesome-icon-with-text v-for="(mailDto, index) in lastProcessedEmails" :key="index">

          <template #icon>
            <i class="font-weight-bold">
              <i v-if="mailDto.status === mailStatusSent"         :class="fontawesomeIconClassesSent"></i>
              <i v-else-if="mailDto.status === mailStatusPending" :class="fontawesomeIconClassesPending"></i>
              <i v-else                                           :class="fontawesomeIconClassesError"></i>
            </i>
          </template>

          <template #title>
            {{ mailDto.subject }}
          </template>

          <template #title-context>
            {{ mailDto.created }}
          </template>
        </row-fontawesome-icon-with-text>

      </div>
    </template>
  </card-with-title>

</template>

<!-- Script -->
<script>
import SymfonyRoutes                     from '../../../../../core/symfony/SymfonyRoutes';
import GetLastProcessedEmailsResponseDto from '../../../../../core/dto/api/internal/GetLastProcessedEmailsResponseDto';
import MailDto                           from '../../../../../core/dto/modules/mailing/MailDto';

import CardWithTitleComponent              from '../../../../base-layout/components/cards/card-with-title';
import RowFontawesomeIconWithTextComponent from '../../../../other/row-fontawesome-icon-with-text';

import StringUtils                         from "../../../../../core/utils/StringUtils";
import TranslationsService                 from "../../../../../core/services/TranslationsService"

let translationService = new TranslationsService();

export default {
  components: {
    'card-with-title'                : CardWithTitleComponent,
    'row-fontawesome-icon-with-text' : RowFontawesomeIconWithTextComponent,
  },
  data(){
    return {
      lastProcessedEmails : [],
      isSpinnerVisible    : true,
    }
  },
  computed: {
    fontawesomeIconClassesPending: function(){
      return "text-warning fas fa-clock";
    },
    fontawesomeIconClassesSent: function(){
      return "text-success fas fa-check-circle";
    },
    fontawesomeIconClassesError: function(){
      return "text-danger fas fa-times-circle";
    },
    mailStatusError: function(){
      return MailDto.STATUS_ERROR;
    },
    mailStatusSent: function(){
      return MailDto.STATUS_SENT;
    },
    mailStatusPending: function(){
      return MailDto.STATUS_PENDING;
    },
    widgetTitleTranslation: function(){
      return translationService.getTranslationForString('pages.dashboard.overview.widgets.lastProcessedEmails.header.label');
    }
  },
  methods: {
    /**
     * @description will fetch the emails which data will be then shown in the widget
     */
    getLastProcessedEmails(){
      let countOfFetchedLastProcessedEmails = 5;

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_LAST_PROCESSED_EMAILS, {
        [SymfonyRoutes.GET_LAST_PROCESSED_EMAILS_PARAM_EMAILS_COUNT] : countOfFetchedLastProcessedEmails,
      });

      this.axios.get(url).then( (response) => {
        let getLastProcessedEmailsResponseDto = GetLastProcessedEmailsResponseDto.fromAxiosResponse(response);
        let emailsJsons                       = getLastProcessedEmailsResponseDto.emailsJsons;
        let emailsDtos                        = [];
        let subjectMaxCharactersCount         = 10;

        for(let index in emailsJsons){
          let mailJson = emailsJsons[index];
          let emailDto = MailDto.fromJson(mailJson);

          emailDto.subject = StringUtils.substringAndAddDots(emailDto.subject, subjectMaxCharactersCount);
          emailsDtos.push(emailDto);
        }

        this.lastProcessedEmails = emailsDtos;
        this.isSpinnerVisible    = false;
      })
    }
  },
  beforeMount(){
    this.getLastProcessedEmails();
  }
}
</script>