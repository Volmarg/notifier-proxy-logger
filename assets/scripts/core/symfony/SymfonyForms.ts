/**
 * @description contains names of the symfony forms, used for example to obtain the csrf token for given form
 *              where `AddMailAccountForm` = `add_mail_account_form`
 */
export default class SymfonyForms
{
    static readonly SEND_TEST_MAIL_FORM_BLOCK_NAME: string      = "send_test_mail_form";
    static readonly MAIL_ACCOUNT_FORM_BLOCK_NAME: string        = "mail_account_form";
    static readonly ADD_DISCORD_WEBHOOK_FORM_BLOCK_NAME: string = "add_discord_webhook_form";
    static readonly SEND_TEST_DISCORD_MESSAGE_FORM: string      = "send_test_discord_message_form";

}