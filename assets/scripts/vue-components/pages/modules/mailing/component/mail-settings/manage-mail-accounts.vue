<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ headerTranslatedString }} </h2>

        <information-text-for-component :displayed-text="informationTranslatedTextString" />

        <section class="">

          <volt-table
              :headers="tableHeaders"
              :rows-data="allEmails"
              @pagination-button-clicked="onPaginationButtonClickedHandler"
              @handle-showing-table-data-for-pagination-and-result="handleDataForPaginationAndSearch"
              @search-for-string-in-table-cells="searchForStringInTableCells"
              ref="table"
          >
            <volt-table-row
                v-for="(mailAccountDto, index) in currentlyVisibleDataInTable"
                :key="index"
                :row-data="mailAccountDto"
                :skipped-keys="skippedDtoProperties"
                :additional-row-classes="additionalRowClasses(mailAccountDto)"
            >
                <!-- default slot insertion -->
                <volt-cell>
                  <template #cellValue>
                    <span v-if="!(mailAccountDto.name === defaultAccountName && isDemo)">
                      <span class="action-button-wrapper">
                        <edit-action @on-edit-action-clicked="onEditActionClicked(mailAccountDto.id)"/>
                      </span>
                      <span class="action-button-wrapper" style="margin-left: 13px;">
                        <remove-action  @on-remove-action-clicked="onRemoveActionClicked(mailAccountDto.id)"/>
                      </span>
                    </span>
                  </template>
                </volt-cell>

                <!-- Dialogs -->
                <section v-if="!(mailAccountDto.name === defaultAccountName && isDemo)">
                  <!-- Edit Dialog -->
                  <material-dialog
                      :ref="'editDialog_' + mailAccountDto.id"
                      :accept-button-translation-string="'mainPageComponents.dialog.buttons.update'"
                      :min-width="'600px'"
                      :hide-on-error="false"
                      @material-modal-confirm-button-click="onMaterialEditModalConfirmButtonClick(mailAccountDto.id)"
                  >
                    <!-- Client -->
                    <material-input-field
                        :display-block="true"
                        :label="clientTranslatedString"
                        :value="mailAccountDto.client"
                        :ref="'materialEditModalClientInput_' + mailAccountDto.id"
                        :error-message="clientErrorMessage"
                    />

                    <!-- Name -->
                    <material-input-field
                        :display-block="true"
                        :margin-top="3"
                        :label="nameTranslatedString"
                        :value="mailAccountDto.name"
                        :ref="'materialEditModalNameInput_' + mailAccountDto.id"
                        :error-message="nameErrorMessage"
                    />

                    <!-- Login -->
                    <material-input-field
                        :display-block="true"
                        :margin-top="3"
                        :label="loginTranslatedString"
                        :value="mailAccountDto.login"
                        :ref="'materialEditModalLoginInput_' + mailAccountDto.id"
                        :error-message="loginErrorMessage"
                    />

                    <!-- Password -->
                    <material-input-field
                        :display-block="true"
                        :margin-top="3"
                        :label="passwordTranslatedString"
                        :value="mailAccountDto.password"
                        :ref="'materialEditModalPasswordInput_' + mailAccountDto.id" :type="'password'"
                        :error-message="passwordErrorMessage"
                    />
                  </material-dialog>

                  <!-- Remove Dialog -->
                  <material-dialog
                      :ref="'removeDialog_' + mailAccountDto.id"
                      :accept-button-translation-string="'mainPageComponents.dialog.buttons.remove'"
                      :min-width="'300px'"
                      @material-modal-confirm-button-click="onMaterialRemoveModalConfirmButtonClick(mailAccountDto.id)"
                  >
                    <raw-content-component :content="getConfirmationTranslatedString(mailAccountDto)"/>
                  </material-dialog>

                </section>


              </volt-table-row>
          </volt-table>

        </section>

      </div>
    </div>
  </div>
</template>

<!-- Script -->
<script>
import VoltTable                         from '../../../../../table/volt/table';
import VoltTableHead                     from '../../../../../table/volt/table-head';
import VoltTableBody                     from '../../../../../table/volt/table-body';
import VoltTableRow                      from '../../../../../table/volt/table-row';
import VoltCellComponent                 from "../../../../../table/volt/table-cell";
import SemipolarSpinnerComponent         from '../../../../../../vue-components/libs/epic-spinners/semipolar-spinner';
import MaterialInputFieldComponent       from "../../../../../form/components/material/input-field";
import MaterialDesignDialogComponent     from "../../../../../dialog-modal/material/dialog";
import RemoveActionComponent             from "../../../../../actions/remove-action";
import EditActionComponent               from "../../../../../actions/edit-action";
import rawContentComponent               from "../../../../../other/raw-content";
import InformationTextForBlockComponent  from "../../../../../pages/components/information-text-for-block";

import GetAllEmailsAccountsResponseDto  from "../../../../../../core/dto/api/internal/GetAllEmailsAccountsResponseDto";
import BaseInternalApiResponseDto       from "../../../../../../core/dto/api/internal/BaseInternalApiResponseDto";
import CsrfTokenResponseDto             from "../../../../../../core/dto/api/internal/CsrfTokenResponseDto";
import MailAccountDto                   from "../../../../../../core/dto/modules/mailing/MailAccountDto";
import DotenvIsDemoResponseDto          from "../../../../../../core/dto/api/internal/DotenvIsDemoResponseDto";

import TranslationsService              from "../../../../../../core/services/TranslationsService";
import SymfonyRoutes                    from "../../../../../../core/symfony/SymfonyRoutes";
import Notification                     from "../../../../../../libs/mdb5/Notification";
import SymfonyForms                     from "../../../../../../core/symfony/SymfonyForms";

import DtoMixin                         from "../../../../../../core/mixins/dto-mixin";

let translationService = new TranslationsService();
let notification       = new Notification();

export default {
  props: {
    /**
     * @description
     *
     **/
    'triggerMailAccountsFetchFlag': {
      type      : Number,
      required  : true,
      default   : 1
    }
  },
  components: {
    'edit-action'                    : EditActionComponent,
    'remove-action'                  : RemoveActionComponent,
    "volt-table-body"                : VoltTableBody,
    "volt-table-head"                : VoltTableHead,
    "volt-table-row"                 : VoltTableRow,
    "volt-table"                     : VoltTable,
    'volt-cell'                      : VoltCellComponent,
    "semipolar-spinner"              : SemipolarSpinnerComponent,
    'material-input-field'           : MaterialInputFieldComponent,
    'material-dialog'                : MaterialDesignDialogComponent,
    'raw-content-component'          : rawContentComponent,
    'information-text-for-component' : InformationTextForBlockComponent,
  },
  data(){
    return {
      allEmails                   : [],
      originalAllEmailsAccounts   : [],
      currentlyVisibleDataInTable : [],
      isSpinnerVisible            : true,
      clientErrorMessage          : '',
      nameErrorMessage            : '',
      loginErrorMessage           : '',
      passwordErrorMessage        : '',
      isDemo                      : false,
    }
  },
  mixins: [
    DtoMixin
  ],
  computed: {
    tableHeaders: {
      get: function () {
        return [
          this.clientTranslatedString,
          this.nameTranslatedString,
          this.loginTranslatedString,
          this.actionsTranslatedString,
        ];
      }
    },
    skippedDtoProperties: function(){
      return [
        "id",
        "password",
      ];
    },
    headerTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.manageMailAccounts.headers.main')
    },
    removalConfirmationTranslatedString: function(){
      return translationService.getTranslationForString('mainPageComponents.dialog.texts.removalConfirmation');
    },
    removalConfirmationDefaultConnectionTranslatedString: function(){
      return translationService.getTranslationForString('mainPageComponents.dialog.texts.removalConfirmationDefault');
    },
    clientTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.manageMailAccounts.table.headers.client');
    },
    nameTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.manageMailAccounts.table.headers.name');
    },
    loginTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.manageMailAccounts.table.headers.login');
    },
    passwordTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.updateMailAccount.dialog.password');
    },
    actionsTranslatedString(){
      return translationService.getTranslationForString('pages.mailing.manageMailAccounts.table.headers.actions');
    },
    informationTranslatedTextString(){
      return translationService.getTranslationForString('pages.mailing.addMailAccount.texts.general');
    },
    defaultAccountName(){
      return "default";
    }
  },
  methods: {
    /**
     * @description handles the logic on the moment that user clicks on the remove action button
     */
    onRemoveActionClicked(mailAccountDtoId){
      let removeDialogRef = 'removeDialog_' + mailAccountDtoId;
      this.$refs[removeDialogRef].showDialog();
    },
    /**
     * @description handle the logic on the moment that user clicks on the edit action button
     */
    onEditActionClicked(mailAccountDtoId){
      let editDialogRef = 'editDialog_' + mailAccountDtoId;
      this.$refs[editDialogRef].showDialog();
      this.resetEditDialogErrors();
    },
    /**
     * @description triggered when user clicks confirm in the edit dialog
     */
    async onMaterialEditModalConfirmButtonClick(mailAccountDtoId){
      let baseApiResponseDto = null;

      try{
        var csrfToken      = await this.getCsrfToken();
        baseApiResponseDto = await this.sendMailAccountUpdateRequest(csrfToken, mailAccountDtoId);
      }catch(Exception){
        console.warn({
          "message": "Could not update the data on the server",
          "result" : Exception,
        })
      }

      let client   = this.$refs['materialEditModalClientInput_'   + mailAccountDtoId].textFieldValue;
      let name     = this.$refs['materialEditModalNameInput_'     + mailAccountDtoId].textFieldValue;
      let login    = this.$refs['materialEditModalLoginInput_'    + mailAccountDtoId].textFieldValue;
      let password = this.$refs['materialEditModalPasswordInput_' + mailAccountDtoId].textFieldValue;

      this.clientErrorMessage   = ( "undefined" === typeof baseApiResponseDto.invalidFields.client   ? "" : baseApiResponseDto.invalidFields.client);
      this.nameErrorMessage     = ( "undefined" === typeof baseApiResponseDto.invalidFields.name     ? "" : baseApiResponseDto.invalidFields.name);
      this.loginErrorMessage    = ( "undefined" === typeof baseApiResponseDto.invalidFields.login    ? "" : baseApiResponseDto.invalidFields.login);
      this.passwordErrorMessage = ( "undefined" === typeof baseApiResponseDto.invalidFields.password ? "" : baseApiResponseDto.invalidFields.password);

      let indexOfEntityInVisibleDataDtos = this.getDtoIndexForEntityWithId(mailAccountDtoId, this.currentlyVisibleDataInTable);
      let indexOfEntityInAllDtosArray    = this.getDtoIndexForEntityWithId(mailAccountDtoId, this.allEmails);

      let currentlyProcessedMailAccountDtoForTable = this.getDtoForEntityWithId(mailAccountDtoId, this.allEmails, MailAccountDto);
      let currentlyProcessedMailAccountDto         = this.allEmails[indexOfEntityInAllDtosArray];

      // whole data
      currentlyProcessedMailAccountDto.client   = client;
      currentlyProcessedMailAccountDto.name     = name;
      currentlyProcessedMailAccountDto.login    = login;
      currentlyProcessedMailAccountDto.password = password;

      // data used only in table
      currentlyProcessedMailAccountDtoForTable.client   = client;
      currentlyProcessedMailAccountDtoForTable.name     = name;
      currentlyProcessedMailAccountDtoForTable.login    = login;
      currentlyProcessedMailAccountDtoForTable.password = password;

      let currentlyVisibleDataInTableNewData = this.currentlyVisibleDataInTable;
      let allEmailsNewData                   = this.allEmails;

      /**
       * @description table reset is required to trigger vue reactivity in this case,
       *              simply setting new modified array to variable won't work,
       *              it has to be explicitly cleared
       */

      this.currentlyVisibleDataInTable = [];
      this.allEmails                   = [];

      currentlyVisibleDataInTableNewData[indexOfEntityInVisibleDataDtos] = currentlyProcessedMailAccountDtoForTable;
      allEmailsNewData[indexOfEntityInAllDtosArray]                      = currentlyProcessedMailAccountDto;

      this.currentlyVisibleDataInTable = currentlyVisibleDataInTableNewData;
      this.allEmails                   = allEmailsNewData;
    },
    /**
     * @description handles the logic when user clicks the `confirm` button in the modal shown upon clicking `remove action`
     */
    onMaterialRemoveModalConfirmButtonClick(mailAccountDtoId){
      let urlReplacementParams = {
        [SymfonyRoutes.REMOVE_MAIL_ACCOUNT_PARAM_MAIL_ACCOUNT_ID] : mailAccountDtoId
      };
      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.REMOVE_MAIL_ACCOUNT, urlReplacementParams);

      this.axios.get(url).then( (response) => {

        let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);
        if( !baseApiResponseDto.success){
          notification.showRedNotification(baseApiResponseDto.message)
        }else{
          notification.showGreenNotification(baseApiResponseDto.message)
          this.getAllMailAccounts();
        }
      })
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
     * @description will get all mail accounts
     */
    getAllMailAccounts(){

      this.axios.get(SymfonyRoutes.GET_ALL_MAIL_ACCOUNTS).then( (response) => {
        this.isSpinnerVisible = false;

        let getAllEmailAccountsResponseDto = GetAllEmailsAccountsResponseDto.fromAxiosResponse(response);
        let allMailAccountsDto             = getAllEmailAccountsResponseDto.emailsAccountsJsons.map( (emailAccountJson) => {
          return MailAccountDto.fromJson(emailAccountJson);
        });

        this.originalAllEmailsAccounts   = allMailAccountsDto;
        this.allEmails                   = allMailAccountsDto;
        this.currentlyVisibleDataInTable = allMailAccountsDto;

        this.$refs.table.searchInput = "";
      });

    },
    /**
     * @description will return the csrf token which is required upon submitting the form (Internal Symfony Validation Logic)
     *
     * @return Promise
     */
    getCsrfToken(){
      let urlReplacementParams = {
        [SymfonyRoutes.GET_CSRF_TOKEN_PARAM_FORM_NAME]: SymfonyForms.MAIL_ACCOUNT_FORM_BLOCK_NAME
      }

      let url     = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.GET_CSRF_TOKEN, urlReplacementParams);
      let promise = this.axios({
        method : "GET",
        url    : url,
      }).then( (response) => {
        let csrfTokenResponseDto = CsrfTokenResponseDto.fromAxiosResponse(response);
        if( !csrfTokenResponseDto.success ){
          notification.showRedNotification(csrfTokenResponseDto.message);
          return null;
        }

         return csrfTokenResponseDto.csrToken;
      });

      return promise;
    },
    /**
     * @description will send request to update the mail account in the backend
     *
     * @param csrfToken {String}
     * @param mailAccountDtoId {String}
     * @return {Promise}
     */
    async sendMailAccountUpdateRequest(csrfToken, mailAccountDtoId){
      let ajaxData = {
        client   : this.$refs['materialEditModalClientInput_'   + mailAccountDtoId].textFieldValue,
        name     : this.$refs['materialEditModalNameInput_'     + mailAccountDtoId].textFieldValue,
        login    : this.$refs['materialEditModalLoginInput_'    + mailAccountDtoId].textFieldValue,
        password : this.$refs['materialEditModalPasswordInput_' + mailAccountDtoId].textFieldValue,
        _token   : csrfToken,
      };

      let urlReplacementParams = {
        [SymfonyRoutes.UPDATE_MAIL_PARAM_ACCOUNT_ID]: mailAccountDtoId
      };

      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.UPDATE_MAIL, urlReplacementParams);
      let promise = this.axios.post(url, ajaxData).then( (result) => {
        let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(result);

        if(!baseApiResponseDto.success){
          notification.showRedNotification(baseApiResponseDto.message);
        }else{
          notification.showGreenNotification(baseApiResponseDto.message);
        }

        return baseApiResponseDto;
      });

      return promise;
    },
    /**
     * @description returns object with classes for row
     * @return {Object}
     */
    additionalRowClasses(mailAccountDto){
      return {
        'text-danger' : (mailAccountDto.name === 'default')
      }
    },
    /**
     * @description returns the removal confirmation translated string based on the mail account dto
     * @param mailAccountDto {MailAccountDto}
     */
    getConfirmationTranslatedString(mailAccountDto){
      if( "default" === mailAccountDto.name ){
        return this.removalConfirmationDefaultConnectionTranslatedString;
      }else{
        return this.removalConfirmationTranslatedString;
      }
    },
    /**
     * @description will reset the errors in the edit dialog
     */
    resetEditDialogErrors(){
      this.clientErrorMessage   = '';
      this.nameErrorMessage     = '';
      this.loginErrorMessage    = '';
      this.passwordErrorMessage = '';
    },
    /**
     * @description will check is app is currently in demo mode
     */
    checkIsDemo(){
      this.axios.get(SymfonyRoutes.ENV_IS_DEMO).then( (response) => {
        let dotenvIsDemoResponseDto = DotenvIsDemoResponseDto.fromAxiosResponse(response);
        this.isDemo                 = dotenvIsDemoResponseDto.isDemo;
      })
    }
  },
  beforeMount(){
    this.checkIsDemo();
    this.getAllMailAccounts();
  },
  watch:{
    triggerMailAccountsFetchFlag(old, newOne){
      this.getAllMailAccounts();
    }
  }
}
</script>