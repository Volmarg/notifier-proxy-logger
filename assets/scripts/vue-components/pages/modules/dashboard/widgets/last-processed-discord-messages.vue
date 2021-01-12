<!-- Template -->
<template>
  <card-with-title
      :card-title="widgetTitleTranslation"
      :is-spinner-visible="isSpinnerVisible"
  >
    <template #card-body>
      <div v-if="[lastProcessedDiscordMessages.length]">

        <row-fontawesome-icon-with-text
            v-for="(discordMessage, index) in lastProcessedDiscordMessages"
            :key="index"
            :class="{'soft-border-bottom': true}"
            :tippy-root-wrapper-body-content="buildRowFontawesomeTippyBodyContentForDiscordMessage(discordMessage)"
        >

          <template #icon>
            <i class="font-weight-bold">
              <i v-if="discordMessage.status === discordMessageStatusSent"         :class="fontawesomeIconClassesSent"></i>
              <i v-else-if="discordMessage.status === discordMessageStatusPending" :class="fontawesomeIconClassesPending"></i>
              <i v-else                                                            :class="fontawesomeIconClassesError"></i>
            </i>
          </template>

          <template #title>
            {{ discordMessage.messageTitle }}
          </template>

          <template #title-context>
            {{ discordMessage.created }}
          </template>
        </row-fontawesome-icon-with-text>

      </div>
    </template>
  </card-with-title>

</template>

<!-- Script -->
<script>
import GetLastProcessedDiscordMessagesResponseDto from "../../../../../core/dto/api/internal/GetLastProcessedDiscordMessagesResponseDto";
import SymfonyRoutes                              from '../../../../../core/symfony/SymfonyRoutes';
import DiscordMessageDto                          from '../../../../../core/dto/modules/discord/DiscordMessageDto';

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
      lastProcessedDiscordMessages : [],
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
    discordMessageStatusError: function(){
      return DiscordMessageDto.STATUS_ERROR;
    },
    discordMessageStatusSent: function(){
      return DiscordMessageDto.STATUS_SENT;
    },
    discordMessageStatusPending: function(){
      return DiscordMessageDto.STATUS_PENDING;
    },
    widgetTitleTranslation: function(){
      return translationService.getTranslationForString('pages.dashboard.overview.widgets.lastProcessedDiscordMessages.header.label');
    },
    rowFontawesomeTippyMessageContentTranslationContentString: function(){
      return translationService.getTranslationForString('pages.dashboard.overview.widgets.lastProcessedDiscordMessages.tippy.bodyContent.content');
    },
    rowFontawesomeTippyMessageTitleTranslationFromString: function(){
      return translationService.getTranslationForString('pages.dashboard.overview.widgets.lastProcessedDiscordMessages.tippy.bodyContent.title');
    },
  },
  methods: {
    /**
     * @description will fetch the discordMessages which data will be then shown in the widget
     */
    getLastProcessedDiscordMessages(){
      let countOfFetchedLastProcessedDiscordMessages = 5;

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_LAST_PROCESSED_DISCORD_MESSAGES, {
        [SymfonyRoutes.GET_LAST_PROCESSED_DISCORD_MESSAGES_PARAM_MESSAGES_COUNT] : countOfFetchedLastProcessedDiscordMessages,
      });

      this.axios.get(url).then( (response) => {
        let getLastProcessedDiscordMessagesResponseDto = GetLastProcessedDiscordMessagesResponseDto.fromAxiosResponse(response);
        let discordMessagesJsons                       = getLastProcessedDiscordMessagesResponseDto.discordMessagesJsons;
        let discordMessagesDtos                        = [];
        let titleMaxCharactersCount                    = 20;

        for(let index in discordMessagesJsons){
          let discordMessageJson = discordMessagesJsons[index];
          let discordMessageDto  = DiscordMessageDto.fromJson(discordMessageJson);

          discordMessageDto.messageTitle = StringUtils.substringAndAddDots(discordMessageDto.messageTitle, titleMaxCharactersCount);
          discordMessagesDtos.push(discordMessageDto);
        }

        this.lastProcessedDiscordMessages = discordMessagesDtos;
        this.isSpinnerVisible             = false;
      })
    },
    /**
     * @description will build the content of the tippy body - visible upon hovering over the row  in widget
     *
     * @param discordMessage {DiscordMessageDto}
     * @returns {string}
     */
    buildRowFontawesomeTippyBodyContentForDiscordMessage(discordMessage){
      let content = `
        <b>${this.rowFontawesomeTippyMessageTitleTranslationFromString}:</b>
        <br/>
        ${discordMessage.messageContent}
        <br/><br/>
        <b>${this.rowFontawesomeTippyMessageContentTranslationContentString}:</b>
        <br/>
        ${discordMessage.messageTitle}
      `;

      return content;
    }
  },
  created(){
    this.getLastProcessedDiscordMessages();
  }
}
</script>