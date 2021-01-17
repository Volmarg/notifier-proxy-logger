<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>
        <section class="">
          <volt-table
              :headers="tableHeadersTranslations"
              :rows-data="discordWebhooksTableData"
              @pagination-button-clicked="onPaginationButtonClickedHandler"
              @handle-showing-table-data-for-pagination-and-result="handleDataForPaginationAndSearch"
              @search-for-string-in-table-cells="searchForStringInTableCells"
              ref="table"
          >

            <volt-table-row
                v-for="(discordWebhookDto, index) in currentlyVisibleDataInTable"
                :key="index"
                :row-data="discordWebhookDto"
                :tippy-row-body-content="getTippyBodyContentForSingleRow(discordWebhooksDtos[index])"
            >
              <!-- default slot insertion -->
              <volt-cell>
                <template #cellValue>
                  <edit-action @on-edit-action-clicked="onEditActionClicked(discordWebhooksDtos[index].id)"/>
                </template>
              </volt-cell>

              <!-- Dialogs -->
              <section>
                <!-- Edit Dialog -->
                <material-dialog
                    :ref="'editDialog_' + discordWebhooksDtos[index].id"
                    :accept-button-translation-string="'mainPageComponents.dialog.buttons.update'"
                    :min-width="'600px'"
                    @material-modal-confirm-button-click="onMaterialEditModalConfirmButtonClick(discordWebhooksDtos[index].id, index)"
                >

                  <!-- Webhook url -->
                  <material-text-field :display-block="true" :label="webhookUrlTranslatedString"  :value="discordWebhooksDtos[index].webhookUrl"  :ref="'materialEditModalWebhookUrlInput_'   + discordWebhooksDtos[index].id"/>
                  <!-- Webhook name -->
                  <material-text-field :display-block="true" :label="webhookNameTranslatedString" :value="discordWebhooksDtos[index].webhookName" :ref="'materialEditModalWebhookNameInput_'  + discordWebhooksDtos[index].id"/>
                  <!-- Username-->
                  <material-text-field :display-block="true" :label="usernameTranslatedString"    :value="discordWebhooksDtos[index].username"    :ref="'materialEditModalUsernameInput_'     + discordWebhooksDtos[index].id"/>
                  <!-- Description -->
                  <material-text-field :display-block="true" :label="descriptionTranslatedString" :value="discordWebhooksDtos[index].description" :ref="'materialEditModalDescriptionInput_'  + discordWebhooksDtos[index].id"/>
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
import BaseInternalApiResponseDto       from "../../../../core/dto/api/internal/BaseInternalApiResponseDto";
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

import SymfonyRoutes                    from "../../../../core/symfony/SymfonyRoutes";
import TranslationsService              from "../../../../core/services/TranslationsService";
import Dialog                           from "../../../../libs/material/Dialog";
import Notification                     from "../../../../libs/mdb5/Notification";

let translationsService = new TranslationsService();
let dialog              = new Dialog();
let notification        = new Notification();

export default {
  data(){
    return {
      isSpinnerVisible            : true,
      discordWebhooksDtos         : [],
      discordWebhooksTableData    : [],
      currentlyVisibleDataInTable : [],
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

        this.currentlyVisibleDataInTable = discordWebhooksDtosForTable;
        this.discordWebhooksTableData    = discordWebhooksDtosForTable;
        this.discordWebhooksDtos         = discordWebhooksDtos;
        this.isSpinnerVisible            = false;
      });
    },
    /**
     * @description will handle the edit action click for given index of webhook dto
     **/
    onEditActionClicked(webhookEntityId){
      let editDialogRef = 'editDialog_' + webhookEntityId;
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
    },
    /**
     * @description will handle edit modal confirmation button click
     * @param discordWebhookDtoEntityId       String
     * @param indexOfEntityInVisibleDataDtos  Boolean
     */
    onMaterialEditModalConfirmButtonClick(discordWebhookDtoEntityId, indexOfEntityInVisibleDataDtos){

      if(!this.hasDtoEntityWithId(discordWebhookDtoEntityId, this.discordWebhooksDtos)){
        throw {
          "message"          : "Tried to update the discordWebhookDto via the edit dialog but no dto with given entity id was found",
          "expectedEntityId" : discordWebhookDtoEntityId,
        }
      }

      let changedWebhookUrl   = this.$refs['materialEditModalWebhookUrlInput_'  + discordWebhookDtoEntityId].textFieldValue;
      let changedWebhookName  = this.$refs['materialEditModalWebhookNameInput_' + discordWebhookDtoEntityId].textFieldValue;
      let changedUsername     = this.$refs['materialEditModalUsernameInput_'    + discordWebhookDtoEntityId].textFieldValue;
      let changedDescription  = this.$refs['materialEditModalDescriptionInput_' + discordWebhookDtoEntityId].textFieldValue;

      let dataBag = {
        entityId    : discordWebhookDtoEntityId,
        webhookUrl  : changedWebhookUrl,
        webhookName : changedWebhookName,
        username    : changedUsername,
        description : changedDescription,
      }

      let updateDiscordWebhookPromise = this.axios.post(SymfonyRoutes.UPDATE_DISCORD_WEBHOOK, dataBag);
      let indexOfEntityInDto          = this.getDtoIndexForEntityWithId(discordWebhookDtoEntityId, this.discordWebhooksDtos);

      updateDiscordWebhookPromise.then( (result) => {
        let baseApiResponse = BaseInternalApiResponseDto.fromAxiosResponse(result);

        if( !baseApiResponse.success ){
          notification.showRedNotification(baseApiResponse.message);
          return;
        }

        let currentlyProcessedDiscordWebhookDtoForTable = this.getDtoForEntityWithId(discordWebhookDtoEntityId, this.discordWebhooksDtos);
        let currentlyProcessedDiscordWebhookDto         = this.discordWebhooksDtos[indexOfEntityInDto];

        // whole data
        currentlyProcessedDiscordWebhookDto.username    = changedUsername;
        currentlyProcessedDiscordWebhookDto.webhookUrl  = changedWebhookUrl;
        currentlyProcessedDiscordWebhookDto.description = changedDescription;
        currentlyProcessedDiscordWebhookDto.webhookName = changedWebhookName;

        // data used only in table
        currentlyProcessedDiscordWebhookDtoForTable.webhookUrl  = changedWebhookUrl;
        currentlyProcessedDiscordWebhookDtoForTable.webhookName = changedWebhookName;
        currentlyProcessedDiscordWebhookDtoForTable.description = "";
        currentlyProcessedDiscordWebhookDtoForTable.username    = "";
        currentlyProcessedDiscordWebhookDtoForTable.id          = "";

        let currentlyVisibleDataInTableNewData = this.currentlyVisibleDataInTable;
        let discordWebhooksDtosNewData         = this.discordWebhooksDtos;

        /**
         * @description table reset is required to trigger vue reactivity in this case,
         *              simply setting new modified array to variable won't work,getTippyBodyContentForSingleRow
         *              it has to be explicitly cleared
         */

        this.currentlyVisibleDataInTable = [];
        this.discordWebhooksDtos         = [];

        currentlyVisibleDataInTableNewData[indexOfEntityInVisibleDataDtos] = currentlyProcessedDiscordWebhookDtoForTable;
        discordWebhooksDtosNewData[indexOfEntityInDto]                     = currentlyProcessedDiscordWebhookDto;

        this.currentlyVisibleDataInTable = currentlyVisibleDataInTableNewData;
        this.discordWebhooksDtos         = discordWebhooksDtosNewData;

        notification.showGreenNotification(baseApiResponse.message);
      });
    },
    /**
     * @description will return dto for given entity id
     *
     * @param entityId String
     * @param dtoArray Array
     * @return Boolean
     */
    hasDtoEntityWithId(entityId, dtoArray){
      return !(null === this.getDtoForEntityWithId(entityId, dtoArray));
    },
    /**
     * @description will return dto for given entity id, or null if nothing is found
     *
     * @param entityId
     * @param dtoArray Array
     * @return ?DiscordWebhookDto
     */
    getDtoForEntityWithId(entityId, dtoArray){
      let foundEntities = dtoArray.filter( (discordDto) => {
        return (discordDto.id === entityId);
      }, dtoArray)

      if(0 === foundEntities.length){
        return null;
      }

      let foundEntity = foundEntities[0];

      // new object is created to prevent modifying original one, as this is object
      let webhookDto = new DiscordWebhookDto();
      webhookDto.description = foundEntity.description;
      webhookDto.id          = foundEntity.id;
      webhookDto.username    = foundEntity.username;
      webhookDto.webhookName = foundEntity.webhookName;
      webhookDto.webhookUrl  = foundEntity.webhookUrl;

      return webhookDto;
    },
    /**
     * @description Will return the index of element in the dtos array, for given entityId
     *              null is returned if nothing is found
     *
     * @param entityId String
     * @param dtoArray Array
     * @return ?Number
     */
    getDtoIndexForEntityWithId(entityId, dtoArray){
      let indexOfDto    = null
      let indexesOfDtos = Object.keys(dtoArray);

      for(let index of indexesOfDtos){
        let dto = dtoArray[index];
        if(entityId == dto.id){
          indexOfDto = index;
          break;
        }
      }
      return indexOfDto;
    }
  },
  created(){
    this.getAllDiscordWebhooks();
  },
}
</script>