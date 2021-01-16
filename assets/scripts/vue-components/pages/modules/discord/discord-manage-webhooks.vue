<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>
        <section class="">
          <volt-table :headers="tableHeadersTranslations" :rows-data="discordWebhooksTableData">

            <volt-table-row
                v-for="(discordWebhookDto, index) in discordWebhooksTableData"
                :key="index"
                :row-data="discordWebhookDto"
                :tippy-row-body-content="getTippyBodyContentForSingleRow(discordWebhooksDtos[index])"
            >
              <!-- default slot insertion -->
              <volt-cell>
                <template #cellValue>
                  <edit-action @on-edit-action-clicked="onEditActionClicked(index)"/>
                </template>
              </volt-cell>

              <!-- Dialogs -->
              <section>
                <!-- Edit Dialog -->
                <material-dialog :ref="'editDialog_' + index" :accept-button-translation-string="'mainPageComponents.dialog.buttons.update'">
                  <!-- Webhook url -->
                  <material-text-field :display-block="true" :label="webhookUrlTranslatedString"  :value="discordWebhooksDtos[index].webhookUrl"></material-text-field>
                  <!-- Webhook name -->
                  <material-text-field :display-block="true" :label="webhookNameTranslatedString" :value="discordWebhooksDtos[index].webhookName"></material-text-field>
                  <!-- Username url -->
                  <material-text-field :display-block="true" :label="usernameTranslatedString"    :value="discordWebhooksDtos[index].username"></material-text-field>
                  <!-- Description url -->
                  <material-text-field :display-block="true" :label="descriptionTranslatedString" :value="discordWebhooksDtos[index].description"></material-text-field>
                </material-dialog>
              </section>

            </volt-table-row>

          </volt-table>
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
import VoltCellComponent                from '../../../table/volt/table-cell';
import SemipolarSpinnerComponent        from '../../../../vue-components/libs/epic-spinners/semipolar-spinner'
import AddDiscordWebhookFormComponent   from '../../../../vue-components/form/modules/discord/add-discord-webhook-form'
import MaterialTextFieldComponent       from '../../../../vue-components/form/components/material/text-field';
import MaterialDesignDialogComponent    from '../../../../vue-components/dialog-modal/material/dialog';
import VoltTableRowComponent            from "../../../../vue-components/table/volt/table-row";

import EditActionComponent              from '../../../actions/edit-action';

import TranslationsService              from "../../../../core/services/TranslationsService";
import Dialog                           from "../../../../libs/material/Dialog";

let translationsService = new TranslationsService();
let dialog              = new Dialog();

export default {
  data(){
    return {
      isSpinnerVisible         : true,
      discordWebhooksDtos      : [],
      discordWebhooksTableData : [],
    }
  },
  components: {
    'edit-action'              : EditActionComponent,
    'volt-cell'                : VoltCellComponent,
    'volt-table'               : VoltTableComponent,
    'semipolar-spinner'        : SemipolarSpinnerComponent,
    'add-discord-webhook-form' : AddDiscordWebhookFormComponent,
    'material-text-field'      : MaterialTextFieldComponent,
    'material-dialog'          : MaterialDesignDialogComponent,
    'volt-table-row'           : VoltTableRowComponent,
  },
  computed: {
    tableHeadersTranslations: function(){
      return [
        this.webhookUrlTranslatedString,
        this.webhookNameTranslatedString,
        this.actionTranslatedString,
      ];
    },
    descriptionTranslatedString: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.singleRow.tippy.description');
    },
    usernameTranslatedString: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.singleRow.tippy.username');
    },
    mainHeaderDiscordWebhooksTranslation: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.headers.main');
    },
    webhookUrlTranslatedString: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.headers.webhookUrl');
    },
    webhookNameTranslatedString: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.headers.webhookName');
    },
    actionTranslatedString: function(){
      return translationsService.getTranslationForString('actions.label.general');
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
     * @description will handle the edit action click for given index of webhook dto
     **/
    onEditActionClicked(index){
      let editDialogRef = 'editDialog_' + index;
      this.$refs[editDialogRef].dialogInstance.open();
    },
    /**
     * @description creates tippy content for single data row - this means the popup visible upon hovering
     *
     * @param dto {DiscordWebhookDto}
     */
    getTippyBodyContentForSingleRow(dto){
      return `
        <b>${this.descriptionTranslatedString}:</b>
        <br/>
        ${dto.description}
        <br/>
        <br/>
        <b>${this.usernameTranslatedString}:</b>
        <br/>
        ${dto.username}
      `;
    },
  },
  created(){
    this.getAllDiscordWebhooks();
  },
}
</script>