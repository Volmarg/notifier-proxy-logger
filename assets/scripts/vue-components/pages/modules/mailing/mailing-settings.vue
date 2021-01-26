<!-- Template -->
<template>
  <manage-mail-account/>
  <add-mail-account/>
</template>

<!-- Script -->
<script>
import AddMailAccountFormComponent     from '../../../form/modules/mailing/add-mail-account-form';
import ManageMailAccountsComponent     from '../mailing/component/mail-settings/manage-mail-accounts'
import TranslationsService             from "../../../../core/services/TranslationsService";

let translationService = new TranslationsService();

export default {
  components: {
    'add-mail-account'    : AddMailAccountFormComponent,
    'manage-mail-account' : ManageMailAccountsComponent,
  },
  methods: {
    onAddMailAccountFormSubmitted: function(){

    }
  }
}

</script>