<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>
        <section class="">
          <volt-table>
            <volt-table-head :table-headers="tableHeadersTranslations">
            </volt-table-head>
            <volt-table-body v-if="discordWebhooksTableData.length">
              <template v-for="(webhookDtoTableData, index) in discordWebhooksTableData" :key="index">
                <volt-table-row :row-data="webhookDtoTableData" :tippy-row-body-content="buildTippyContentForSingeDiscordWebhookRow(discordWebhooksDtos[index])"/>
              </template>
            </volt-table-body>
          </volt-table>
        </section>
      </div>
    </div>
  </div>

  <br/>
  <add-discord-webhook-form/>
</template>

<!-- Script -->
<script>
import SymfonyRoutes                    from "../../../../core/symfony/SymfonyRoutes";
import GetAllDiscordWebhooksResponseDto from "../../../../core/dto/api/internal/GetAllDiscordWebhooksResponseDto";
import DiscordWebhookDto                from "../../../../core/dto/modules/discord/DiscordWebhookDto";

import VoltTableComponent               from '../../../table/volt/table';
import VoltTableHeadComponent           from '../../../table/volt/table-head';
import VoltTableBodyComponent           from '../../../table/volt/table-body';
import VoltTableRowComponent            from '../../../table/volt/table-row';
import SemipolarSpinnerComponent        from '../../../../vue-components/libs/epic-spinners/semipolar-spinner'
import AddDiscordWebhookFormComponent   from '../../../../vue-components/form/modules/discord/add-discord-webhook-form'

import TranslationsService              from "../../../../core/services/TranslationsService";

let translationsService = new TranslationsService();

export default {
  data(){
    return {
      isSpinnerVisible         : true,
      discordWebhooksDtos      : [],
      discordWebhooksTableData : [],
    }
  },
  components: {
    'volt-table'               : VoltTableComponent,
    'volt-table-head'          : VoltTableHeadComponent,
    'volt-table-body'          : VoltTableBodyComponent,
    'volt-table-row'           : VoltTableRowComponent,
    'semipolar-spinner'        : SemipolarSpinnerComponent,
    'add-discord-webhook-form' : AddDiscordWebhookFormComponent,
  },
  computed: {
    tableHeadersTranslations: function(){
      return translationsService.getTranslationsForStrings([
        'pages.discord.getAllWebhooks.allWebhooksTable.headers.webhookUrl',
        'pages.discord.getAllWebhooks.allWebhooksTable.headers.webhookName',
      ]);
    },
    tableRowTippyDescriptionTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.singleRow.tippy.description');
    },
    tableRowTippyUsernameTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.singleRow.tippy.username');
    },
    mainHeaderDiscordWebhooksTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.headers.main');
    }
  },
  methods: {
    /**
     * @description will fetch the webhooks definitions from the backend
     */
    getAllDiscordWebhooks: function(){

      this.axios.get(SymfonyRoutes.GET_ALL_DISCORD_WEBHOOKS).then( (response) => {

        let getAllDiscordWebhooksResponseDto = GetAllDiscordWebhooksResponseDto.fromAxiosResponse(response);
        let discordWebhooksJsons             = getAllDiscordWebhooksResponseDto.discordWebhooksJsons;
        let discordWebhooksDtosForTable      = [];
        let discordWebhooksDtos              = [];

        for(let index in discordWebhooksJsons){
          let discordWebhookDtoJson     = discordWebhooksJsons[index];
          let discordWebhookDto         = DiscordWebhookDto.fromJson(discordWebhookDtoJson);
          let discordWebhookDtoForTable = DiscordWebhookDto.fromJson(discordWebhookDtoJson);

          /**@description do not show this columns */
          discordWebhookDtoForTable.username    = "";
          discordWebhookDtoForTable.description = "";

          discordWebhooksDtosForTable.push(discordWebhookDtoForTable);
          discordWebhooksDtos.push(discordWebhookDto);
        }

        this.discordWebhooksTableData = discordWebhooksDtosForTable;
        this.discordWebhooksDtos      = discordWebhooksDtos;
        this.isSpinnerVisible         = false;
      });
    },
    /**
     * @description will output the string shown upon hovering over the webhook row
     * @param discordWebhookDto {DiscordWebhookDto}
     */
    buildTippyContentForSingeDiscordWebhookRow(discordWebhookDto){
      let content = `
        <b>${this.tableRowTippyDescriptionTranslation}:</b>
        <br/>
        ${discordWebhookDto.description}
        <br/>
        <br/>
        <b>${this.tableRowTippyUsernameTranslation}:</b>
        <br/>
        ${discordWebhookDto.username}
      `;

      return content;
    }
  },
  created(){
    this.getAllDiscordWebhooks();
  }
}
</script>