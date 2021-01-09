<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>
        <section class="">
          <volt-table :headers="tableHeadersTranslations" :rows-data="discordWebhooksTableData" :tippy-content-for-all-rows-data="buildTippyContentForAllDiscordWebhookRows()" />
        </section>
      </div>
    </div>
  </div>

  <br/>
  <add-discord-webhook-form @discord-manage-webhook-add-webhook-form-submitted="getAllDiscordWebhooks"/>
</template>

<!-- Script -->
<script>
import SymfonyRoutes                    from "../../../../core/symfony/SymfonyRoutes";
import GetAllDiscordWebhooksResponseDto from "../../../../core/dto/api/internal/GetAllDiscordWebhooksResponseDto";
import DiscordWebhookDto                from "../../../../core/dto/modules/discord/DiscordWebhookDto";

import VoltTableComponent               from '../../../table/volt/table';
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
          discordWebhookDtoForTable.id          = "";
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
     */
    buildTippyContentForAllDiscordWebhookRows(){

      let tippyContentForaAllDiscordWebhookRows = [];
      this.discordWebhooksDtos.forEach((dto, index) => {
        let content = `
        <b>${this.tableRowTippyDescriptionTranslation}:</b>
        <br/>
        ${dto.description}
        <br/>
        <br/>
        <b>${this.tableRowTippyUsernameTranslation}:</b>
        <br/>
        ${dto.username}
      `;

        tippyContentForaAllDiscordWebhookRows.push(content);
      })

      return tippyContentForaAllDiscordWebhookRows;
    }
  },
  created(){
    this.getAllDiscordWebhooks();
  }
}
</script>