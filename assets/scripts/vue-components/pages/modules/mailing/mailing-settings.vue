<!-- Template -->
<template>
  <manage-mail-account :trigger-mail-accounts-fetch-flag="triggerFetchMailAccounts"/>
  <add-mail-account @add-mail-account-form-submitted="onAddMailAccountFormSubmitted"/>
</template>

<!-- Script -->
<script>
import AddMailAccountFormComponent     from '../../../form/modules/mailing/add-mail-account-form';
import ManageMailAccountsComponent     from '../mailing/component/mail-settings/manage-mail-accounts'
import TranslationsService             from "../../../../core/services/TranslationsService";

let translationService = new TranslationsService();

export default {
  data(){
    return {
      triggerFetchMailAccounts: 1,
    }
  },
  components: {
    'add-mail-account'    : AddMailAccountFormComponent,
    'manage-mail-account' : ManageMailAccountsComponent,
  },
  methods: {
    onAddMailAccountFormSubmitted: function(){
      this.triggerFetchMailAccounts++;
    }
  }
}

</script>