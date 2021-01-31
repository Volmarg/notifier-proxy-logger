<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ sentDiscordMessagesLabel }} </h2>

        <information-text-for-block :displayed-text="informationTextTranslatedString"/>

        <section class="">
          <volt-table
              :headers="tableHeaders"
              :rows-data="allDataInTable"
              @pagination-button-clicked="onPaginationButtonClickedHandler"
              @handle-showing-table-data-for-pagination-and-result="handleDataForPaginationAndSearch"
              @search-for-string-in-table-cells="searchForStringInTableCells"
              ref="table"
          >
            <volt-table-row
                v-for="(discordMessageDto, index) in currentlyVisibleDataInTable"
                :key="index"
                :row-data="discordMessageDto"
                :tippy-row-body-content="buildRowTippyBodyContentForDiscordMessage(allDiscordMessages[index])"
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
import VoltTable                        from '../../../table/volt/table';
import VoltTableHead                    from '../../../table/volt/table-head';
import VoltTableBody                    from '../../../table/volt/table-body';
import VoltTableRow                     from '../../../table/volt/table-row';
import SemipolarSpinnerComponent        from '../../../../vue-components/libs/epic-spinners/semipolar-spinner';
import InformationTextForBlockComponent from '../../../../vue-components/pages/components/information-text-for-block';

import TranslationsService     from "../../../../core/services/TranslationsService";
import SymfonyRoutes           from "../../../../core/symfony/SymfonyRoutes";

import DiscordMessageDto                from "../../../../core/dto/modules/discord/DiscordMessageDto";
import GetAllDiscordMessagesResponseDto from "../../../../core/dto/api/internal/GetAllDiscordMessagesResponseDto";

let translationService = new TranslationsService();

export default {
  components: {
    "volt-table-body"            : VoltTableBody,
    "volt-table-head"            : VoltTableHead,
    "volt-table-row"             : VoltTableRow,
    "volt-table"                 : VoltTable,
    "semipolar-spinner"          : SemipolarSpinnerComponent,
    "information-text-for-block" : InformationTextForBlockComponent,
  },
  created(){
    this.retrieveAllDiscordWebhooks();
  },
  data(){
    return {
      allDiscordMessages          : [],
      currentlyVisibleDataInTable : [],
      allDataInTable              : [],
      isSpinnerVisible            : true,
    }
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
    skippedDtoProperties() {
      return [
          "messageContent"
      ]
    },
    informationTextTranslatedString: function(){
      return translationService.getTranslationForString('pages.discord.history.texts.general');
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

        this.currentlyVisibleDataInTable = this.processDiscordWebhooksDataForDisplayingInTable(tableDataDtos);
        this.allDataInTable              = this.processDiscordWebhooksDataForDisplayingInTable(tableDataDtos);
        this.isSpinnerVisible            = false;
      })
    },
    /**
     * @description build the content for Tippy.js - visible upon hovering over the row in history table
     *
     * @param discordMessageDto {DiscordMessageDto}
     */
    buildRowTippyBodyContentForDiscordMessage(discordMessageDto){
      return `
          <b>${this.tippyBodyContentTranslationContentString}:</b>
          <br/>
          ${discordMessageDto.messageContent}
        `;
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