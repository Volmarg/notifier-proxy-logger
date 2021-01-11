<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ sentDiscordMessagesLabel }} </h2>

        <section class="">
          <volt-table :headers="tableHeaders" :rows-data="tableData" :tippy-content-for-all-rows-data="buildRowTippyBodyContentForDiscordMessage()" />
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

import DiscordMessageDto                from "../../../../core/dto/modules/discord/DiscordMessageDto";
import GetAllDiscordMessagesResponseDto from "../../../../core/dto/api/internal/GetAllDiscordMessagesResponseDto";

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
    this.retrieveAllDiscordWebhooks();
  },
  data(){
    return {
      allDiscordMessages : [],
      tableData          : [],
      isSpinnerVisible   : true,
    }
  },
  methods: {
    /**
     * @description retrieve all Discord Webhooks for further processing like for example display in table
     */
    retrieveAllDiscordWebhooks(){
      this.axios.get(SymfonyRoutes.GET_ALL_DISCORD_MESSAGES).then( (response) => {
        let allDiscordWebhooksResponse = GetAllDiscordMessagesResponseDto.fromAxiosResponse(response);
        let discordMessagesJsons       = allDiscordWebhooksResponse.discordMessagesJsons;

        this.allDiscordMessages = discordMessagesJsons.map( (json) => {
          return DiscordMessageDto.fromJson(json);
        });

        let tableDataDtos = discordMessagesJsons.map( (json) => {
          return DiscordMessageDto.fromJson(json);
        });

        this.tableData        = this.processDiscordWebhooksDataForDisplayingInTable(tableDataDtos);
        this.isSpinnerVisible = false;
      })
    },
    /**
     * @description build the content for Tippy.js - visible upon hovering over the row in history table
     */
    buildRowTippyBodyContentForDiscordMessage(){

      let tippyContentForaAllDiscordWebhooksRows = this.cachedAllDiscordMessages.map( (dto) => {
        return `
          <b>${this.tippyBodyContentTranslationContentString}:</b>
          <br/>
          ${dto.messageContent}
        `;
      });

      return tippyContentForaAllDiscordWebhooksRows;
    },
    /**
     * @description will filter the data for displaying in table, either set empty value to skip them or add special
     *              formatting/styling
     *
     * @returns {Array<DiscordMessageDto>}
     */
    processDiscordWebhooksDataForDisplayingInTable(discordMessageDtos){
        let filteredTableData = [];

        discordMessageDtos.forEach( (discordMessage, index) => {

          /** @description these columns have to be skipped */
          discordMessage.messageContent = "";

          let classes = "";
          switch(discordMessage.status){

            case DiscordMessageDto.STATUS_PENDING:
              classes+= " npl-text-color-dark-orange";
            break;

            case DiscordMessageDto.STATUS_SENT:
              classes+= " text-success";
            break;

            default:
              classes+= " text-danger";
            break;
          }

          discordMessage.status = `<b class="${classes}">${discordMessage.status}</b>`;

          filteredTableData.push(discordMessage);
        });

        return filteredTableData;
      },
  },
  computed: {
    tableHeaders: {
      get: function () {
        return translationService.getTranslationsForStrings([
            'pages.discord.history.table.headers.messageTitle.label',
            'pages.discord.history.table.headers.status.label',
            'pages.discord.history.table.headers.created.label'
        ]);
      },
    },
    sentDiscordMessagesLabel: {
      get: function () {
        return translationService.getTranslationForString('pages.discord.history.labels.main');
      }
    },
    cachedAllDiscordMessages: {
      get: function() {
        return this.allDiscordMessages;
      },
      set: function (discordMessages) {
        this.allDiscordMessages = discordMessages;
      }
    },
    tippyBodyContentTranslationContentString: function(){
      return translationService.getTranslationForString('pages.discord.history.table.rows.tippyContent.contentString');
    },
  }
}
</script>