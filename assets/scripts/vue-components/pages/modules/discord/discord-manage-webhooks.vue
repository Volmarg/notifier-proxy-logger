<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ mainHeaderDiscordWebhooksTranslation }}  </h2>

        <information-text-for-block :displayed-text="tableManagementInformationTranslatedString"/>

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
                v-for="discordWebhookDto in currentlyVisibleDataInTable"
                :key="discordWebhookDto.id"
                :row-data="discordWebhookDto"
                :tippy-row-body-content="getTippyBodyContentForSingleRow(discordWebhookDto)"
                :skipped-keys="skippedDtoProperties"
            >
              <!-- default slot insertion -->
              <volt-cell>
                <template #cellValue>
                  <span class="action-button-wrapper">
                    <edit-action @on-edit-action-clicked="onEditActionClicked(discordWebhookDto.id)"/>
                  </span>
                  <span class="action-button-wrapper" style="margin-left: 13px;">
                    <remove-action  @on-remove-action-clicked="onRemoveActionClicked(discordWebhookDto.id)"/>
                  </span>
                </template>
              </volt-cell>

              <!-- Dialogs -->
              <section>
                <!-- Edit Dialog -->
                <material-dialog
                    :ref="'editDialog_' + discordWebhookDto.id"
                    :accept-button-translation-string="'mainPageComponents.dialog.buttons.update'"
                    :hide-on-error="false"
                    :min-width="'600px'"
                    @material-modal-confirm-button-click="onMaterialEditModalConfirmButtonClick(discordWebhookDto.id)"
                >

                  <!-- Webhook url -->
                  <material-input-field
                      :display-block="true"
                      :label="webhookUrlTranslatedString"
                      :value="discordWebhookDto.webhookUrl"
                      :ref="'materialEditModalWebhookUrlInput_'  + discordWebhookDto.id"
                      :error-message="urlErrorMessage"
                  />
                  <!-- Webhook name -->
                  <material-input-field
                      :display-block="true"
                      :margin-top="3"
                      :label="webhookNameTranslatedString"
                      :value="discordWebhookDto.webhookName"
                      :ref="'materialEditModalWebhookNameInput_' + discordWebhookDto.id"
                      :error-message="nameErrorMessage"
                  />
                  <!-- Username-->
                  <material-input-field
                      :display-block="true"
                      :margin-top="3"
                      :label="usernameTranslatedString"
                      :value="discordWebhookDto.username"
                      :ref="'materialEditModalUsernameInput_'    + discordWebhookDto.id"
                      :error-message="usernameErrorMessage"
                  />
                  <!-- Description -->
                  <material-input-field
                      :display-block="true"
                      :margin-top="3"
                      :label="descriptionTranslatedString"
                      :value="discordWebhookDto.description"
                      :ref="'materialEditModalDescriptionInput_' + discordWebhookDto.id"
                      :error-message="descriptionErrorMessage"
                  />
                </material-dialog>

                <!-- Remove Dialog -->
                <material-dialog
                    :ref="'removeDialog_' + discordWebhookDto.id"
                    :accept-button-translation-string="'mainPageComponents.dialog.buttons.remove'"
                    :min-width="'300px'"
                    @material-modal-confirm-button-click="onMaterialRemoveModalConfirmButtonClick(discordWebhookDto.id)"
                >
                  {{ removalConfirmationTranslatedString }}
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
import MaterialInputFieldComponent      from '../../../form/components/material/input-field';
import MaterialDesignDialogComponent    from '../../../../vue-components/dialog-modal/material/dialog';
import VoltTableRowComponent            from "../../../../vue-components/table/volt/table-row";
import EditActionComponent              from '../../../actions/edit-action';
import RemoveActionComponent            from '../../../actions/remove-action';
import InformationTextForBlockComponent from "../../../pages/components/information-text-for-block";

import SymfonyRoutes                    from "../../../../core/symfony/SymfonyRoutes";
import TranslationsService              from "../../../../core/services/TranslationsService";
import Notification                     from "../../../../libs/mdb5/Notification";

import DtoMixin                         from "../../../../core/mixins/dto-mixin";

let translationsService = new TranslationsService();
let notification        = new Notification();

export default {
  data(){
    return {
      isSpinnerVisible            : true,
      discordWebhooksDtos         : [],
      discordWebhooksTableData    : [],
      currentlyVisibleDataInTable : [],
      urlErrorMessage             : "",
      nameErrorMessage            : "",
      usernameErrorMessage        : "",
      descriptionErrorMessage     : "",
    }
  },
  components: {
    'information-text-for-block' : InformationTextForBlockComponent,
    'remove-action'              : RemoveActionComponent,
    'edit-action'                : EditActionComponent,
    'volt-cell'                  : VoltCellComponent,
    'volt-table'                 : VoltTableComponent,
    'semipolar-spinner'          : SemipolarSpinnerComponent,
    'add-discord-webhook-form'   : AddDiscordWebhookFormComponent,
    'material-input-field'       : MaterialInputFieldComponent,
    'material-dialog'            : MaterialDesignDialogComponent,
    'volt-table-row'             : VoltTableRowComponent,
  },
  mixins: [
    DtoMixin
  ],
  computed: {
    skippedDtoProperties: function(){
      return [
        'id',
        'username',
        'description',
      ]
    },
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
    },
    removalConfirmationTranslatedString: function(){
      return translationsService.getTranslationForString('mainPageComponents.dialog.texts.removalConfirmation');
    },
    tableManagementInformationTranslatedString: function(){
      return translationsService.getTranslationForString('pages.discord.getAllWebhooks.allWebhooksTable.texts.general');
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

          discordWebhooksDtosForTable.push(discordWebhookDtoForTable);
          discordWebhooksDtos.push(discordWebhookDto);
        }

        this.currentlyVisibleDataInTable = [];
        this.discordWebhooksTableData    = [];
        this.discordWebhooksDtos         = [];

        this.currentlyVisibleDataInTable = discordWebhooksDtosForTable;
        this.discordWebhooksTableData    = discordWebhooksDtosForTable;
        this.discordWebhooksDtos         = discordWebhooksDtos;
        this.isSpinnerVisible            = false;

        this.$refs.table.searchInput     = "";
      });
    },
    /**
     * @description will handle the edit action click for given index of webhook dto
     *
     * @param webhookEntityId String
     */
    onEditActionClicked(webhookEntityId){
      let editDialogRef = 'editDialog_' + webhookEntityId;
      this.$refs[editDialogRef].dialogInstance.open();
      this.clearEditDialogErrorMessages();
    },
    /**
     * @description will handle the remove action click for given index of webhook dto
     *
     * @param webhookEntityId String
     */
    onRemoveActionClicked(webhookEntityId){
      let removeDialogRef = 'removeDialog_' + webhookEntityId;
      this.$refs[removeDialogRef].dialogInstance.open();
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
     */
    async onMaterialEditModalConfirmButtonClick(discordWebhookDtoEntityId){

      if( !this.hasDtoEntityWithId(discordWebhookDtoEntityId, this.currentlyVisibleDataInTable, DiscordWebhookDto) ){
        throw {
          "message"          : "Tried to update the discordWebhookDto via the edit dialog but no dto with given entity id was found",
          "expectedEntityId" : discordWebhookDtoEntityId,
        }
      }
      let indexOfEntityInVisibleDataDtos = this.getDtoIndexForEntityWithId(discordWebhookDtoEntityId, this.currentlyVisibleDataInTable);
      let indexOfEntityInAllDtosArray    = this.getDtoIndexForEntityWithId(discordWebhookDtoEntityId, this.discordWebhooksDtos);

      let changedWebhookUrl  = this.$refs['materialEditModalWebhookUrlInput_'  + discordWebhookDtoEntityId].textFieldValue;
      let changedWebhookName = this.$refs['materialEditModalWebhookNameInput_' + discordWebhookDtoEntityId].textFieldValue;
      let changedUsername    = this.$refs['materialEditModalUsernameInput_'    + discordWebhookDtoEntityId].textFieldValue;
      let changedDescription = this.$refs['materialEditModalDescriptionInput_' + discordWebhookDtoEntityId].textFieldValue;

      let dataBag = {
        entityId    : discordWebhookDtoEntityId,
        webhookUrl  : changedWebhookUrl,
        webhookName : changedWebhookName,
        username    : changedUsername,
        description : changedDescription,
      }

      this.axios.post(SymfonyRoutes.UPDATE_DISCORD_WEBHOOK, dataBag).then( (result) => {
        let baseApiResponse = BaseInternalApiResponseDto.fromAxiosResponse(result);

        if( !baseApiResponse.success ){
          notification.showRedNotification(baseApiResponse.message);
          this.nameErrorMessage        = ( "undefined" === typeof baseApiResponse.invalidFields.webhookName ? "" : baseApiResponse.invalidFields.webhookName);
          this.urlErrorMessage         = ( "undefined" === typeof baseApiResponse.invalidFields.webhookUrl  ? "" : baseApiResponse.invalidFields.webhookUrl);
          this.usernameErrorMessage    = ( "undefined" === typeof baseApiResponse.invalidFields.username    ? "" : baseApiResponse.invalidFields.username);
          this.descriptionErrorMessage = ( "undefined" === typeof baseApiResponse.invalidFields.description ? "" : baseApiResponse.invalidFields.description);
          return;
        }

        let currentlyProcessedDiscordWebhookDtoForTable = this.getDtoForEntityWithId(discordWebhookDtoEntityId, this.discordWebhooksDtos, DiscordWebhookDto);
        let currentlyProcessedDiscordWebhookDto         = this.discordWebhooksDtos[indexOfEntityInVisibleDataDtos];

        // whole data
        currentlyProcessedDiscordWebhookDto.username    = changedUsername;
        currentlyProcessedDiscordWebhookDto.webhookUrl  = changedWebhookUrl;
        currentlyProcessedDiscordWebhookDto.description = changedDescription;
        currentlyProcessedDiscordWebhookDto.webhookName = changedWebhookName;

        // data used only in table
        currentlyProcessedDiscordWebhookDtoForTable.webhookUrl   = changedWebhookUrl;
        currentlyProcessedDiscordWebhookDtoForTable.webhookName  = changedWebhookName;
        currentlyProcessedDiscordWebhookDtoForTable.description  = changedDescription;
        currentlyProcessedDiscordWebhookDtoForTable.username     = changedUsername;

        let currentlyVisibleDataInTableNewData = this.currentlyVisibleDataInTable;
        let discordWebhooksDtosNewData         = this.discordWebhooksDtos;

        /**
         * @description table reset is required to trigger vue reactivity in this case,
         *              simply setting new modified array to variable won't work,
         *              it has to be explicitly cleared
         */

        this.currentlyVisibleDataInTable = [];
        this.discordWebhooksDtos         = [];

        currentlyVisibleDataInTableNewData[indexOfEntityInVisibleDataDtos] = currentlyProcessedDiscordWebhookDtoForTable;
        discordWebhooksDtosNewData[indexOfEntityInAllDtosArray]            = currentlyProcessedDiscordWebhookDto;

        this.currentlyVisibleDataInTable = currentlyVisibleDataInTableNewData;
        this.discordWebhooksDtos         = discordWebhooksDtosNewData;

        notification.showGreenNotification(baseApiResponse.message);
      });
    },
    /**
     * @description will handle the case where the `remove` button in modal is being clicked (for Removal modal)
     *
     * @param discordWebhookDtoEntityId String
     */
    onMaterialRemoveModalConfirmButtonClick(discordWebhookDtoEntityId){

      let replacedParams = {
        [SymfonyRoutes.REMOVE_DISCORD_WEBHOOK_PARAM_WEBHOOK_ID]: discordWebhookDtoEntityId,
      }

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.REMOVE_DISCORD_WEBHOOK, replacedParams);

      this.axios.get(url). then( (response) => {
        let baseApiResponse = BaseInternalApiResponseDto.fromAxiosResponse(response);

        if(baseApiResponse.success){
          notification.showGreenNotification(baseApiResponse.message);
          this.getAllDiscordWebhooks()
        }else{
          notification.showRedNotification(baseApiResponse.message);
        }

      })
    },
    /**
     * @description will clear the error messages for edit dialog
     */
    clearEditDialogErrorMessages(){
      this.urlErrorMessage         = "";
      this.nameErrorMessage        = "";
      this.usernameErrorMessage    = "";
      this.descriptionErrorMessage = "";
    }
  },
  created(){
    this.getAllDiscordWebhooks();
  },
}
</script>

<!-- Styles -->
<style scoped>
.action-button-wrapper {
  display: inline-block;
}
</style>