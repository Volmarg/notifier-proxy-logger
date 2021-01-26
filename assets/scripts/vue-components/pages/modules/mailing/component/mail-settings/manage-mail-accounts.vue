<!-- Template -->
<template>
  <semipolar-spinner v-show="isSpinnerVisible"/>

  <div class="row">
    <div class="col-12 col-xl-12">
      <div class="card card-body bg-white border-light shadow-sm mb-4">
        <h2 class="h5 mb-4"> {{ headerTranslatedString }} </h2>

        <section class="">

          <volt-table
              :headers="tableHeaders"
              :rows-data="originalAllEmailsAccounts"
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
            >
                <!-- default slot insertion -->
                <volt-cell>
                  <template #cellValue>
                    <span class="action-button-wrapper" style="margin-left: 13px;">
                      <remove-action  @on-remove-action-clicked="onRemoveActionClicked(mailAccountDto.id)"/>
                    </span>
                  </template>
                </volt-cell>

                <!-- Dialogs -->
                <section>

                  <!-- Remove Dialog -->
                  <material-dialog
                      :ref="'removeDialog_' + mailAccountDto.id"
                      :accept-button-translation-string="'mainPageComponents.dialog.buttons.remove'"
                      :min-width="'300px'"
                      @material-modal-confirm-button-click="onMaterialRemoveModalConfirmButtonClick(mailAccountDto.id)"
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
</template>

<!-- Script -->
<script>
import VoltTable                        from '../../../../../table/volt/table';
import VoltTableHead                    from '../../../../../table/volt/table-head';
import VoltTableBody                    from '../../../../../table/volt/table-body';
import VoltTableRow                     from '../../../../../table/volt/table-row';
import VoltCellComponent                from "../../../../../table/volt/table-cell";
import SemipolarSpinnerComponent        from '../../../../../../vue-components/libs/epic-spinners/semipolar-spinner';
import MaterialTextFieldComponent       from "../../../../../form/components/material/text-field";
import MaterialDesignDialogComponent    from "../../../../../dialog-modal/material/dialog";
import RemoveActionComponent            from "../../../../../actions/remove-action";

import GetAllEmailsAccountsResponseDto  from "../../../../../../core/dto/api/internal/GetAllEmailsAccountsResponseDto";
import BaseInternalApiResponseDto       from "../../../../../../core/dto/api/internal/BaseInternalApiResponseDto";
import MailAccountDto                   from "../../../../../../core/dto/modules/mailing/MailAccountDto";

import TranslationsService              from "../../../../../../core/services/TranslationsService";
import SymfonyRoutes                    from "../../../../../../core/symfony/SymfonyRoutes";
import Notification                     from "../../../../../../libs/mdb5/Notification";

let translationService = new TranslationsService();
let notification       = new Notification();

export default {
  props: {
    /**
     * @description
     *
     **/
    'trigger-mail-accounts-fetch-flag': {
      type      : Number,
      required  : true,
      default   : 1
    }
  },
  components: {
    'remove-action'       : RemoveActionComponent,
    "volt-table-body"     : VoltTableBody,
    "volt-table-head"     : VoltTableHead,
    "volt-table-row"      : VoltTableRow,
    "volt-table"          : VoltTable,
    'volt-cell'           : VoltCellComponent,
    "semipolar-spinner"   : SemipolarSpinnerComponent,
    'material-text-field' : MaterialTextFieldComponent,
    'material-dialog'     : MaterialDesignDialogComponent,
  },
  data(){
    return {
      allEmails                   : [],
      originalAllEmailsAccounts   : [],
      currentlyVisibleDataInTable : [],
      isSpinnerVisible            : true,
    }
  },
  computed: {
    tableHeaders: {
      get: function () {
        return translationService.getTranslationsForStrings([
          'pages.mailing.manageMailAccounts.table.headers.name',
          'pages.mailing.manageMailAccounts.table.headers.client',
          'pages.mailing.manageMailAccounts.table.headers.login',
          'pages.mailing.manageMailAccounts.table.headers.actions',
        ]);
      },
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
    }
  },
  methods: {
    /**
     * @description handles the logic on the moment that user clicks on the remove action button
     */
    onRemoveActionClicked(mailAccountDtoId){
      let removeDialogRef = 'removeDialog_' + mailAccountDtoId;
      this.$refs[removeDialogRef].dialogInstance.open();
    },
    /**
     * @description handles the logic when user clicks the `confirm` button in the modal shown upon clicking `remove action`
     */
    onMaterialRemoveModalConfirmButtonClick(mailAccountDtoId){
      let urlReplacementParams = {
        [SymfonyRoutes.REMOVE_MAIL_ACCOUNT_PARAM_WEBHOOK_ID] : mailAccountDtoId
      };
      let url = SymfonyRoutes.buildUrlWithReplacedParams(SymfonyRoutes.REMOVE_MAIL_ACCOUNT, urlReplacementParams);

      this.axios.get(url).then( (response) => {

        let baseApiResponseDto = BaseInternalApiResponseDto.fromAxiosResponse(response);
        if( !baseApiResponseDto.success){
          notification.showRedNotification(baseApiResponseDto.message)
        }else{
          notification.showGreenNotification(baseApiResponseDto.message)
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
        this.currentlyVisibleDataInTable = allMailAccountsDto;
      });

    }
  },
  beforeMount(){
    this.getAllMailAccounts();
  },
}
</script>