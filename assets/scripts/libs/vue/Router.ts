import {createRouter, createWebHistory, RouterOptions}  from 'vue-router';
import MailingModuleOverviewComponent                   from '../../vue-components/pages/modules/mailing/mailing-test-sending.vue';
import MailingModuleHistoryComponent                    from '../../vue-components/pages/modules/mailing/mailing-history.vue';
import MailSettingsComponent                            from '../../vue-components/pages/modules/mailing/mailing-settings.vue';
import DashboardOverviewComponent                       from '../../vue-components/pages/modules/dashboard/overview.vue';
import DiscordWebhookManagementComponent                from '../../vue-components/pages/modules/discord/discord-manage-webhooks.vue';
import DiscordTestSendingComponent                      from '../../vue-components/pages/modules/discord/discord-test-sending.vue';
import DiscordHistoryComponent                          from '../../vue-components/pages/modules/discord/discord-messages-history.vue';

/**
 * @description Router used by vue
 */
export default class Router {

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<Object> = [
        {
            path      : '/modules/mailing/overview',
            component : MailingModuleOverviewComponent,
            name      : "modules_mailing_overview"
        },
        {
            path      : "/modules/mailing/history",
            component : MailingModuleHistoryComponent,
            name      : "modules_mailing_history"
        },
        {
            path      : '/modules/dashboard/overview',
            component : DashboardOverviewComponent,
            name      : "modules_dashboard_overview"
        },
        {
            path      : '/modules/discord/manage-webhooks',
            component : DiscordWebhookManagementComponent,
            name      : 'modules_discord_webhooks_manage',
        },
        {
            path      : "/modules/discord/test-sending",
            component : DiscordTestSendingComponent,
            name      : "modules_discord_test_sending"
        },
        {
            path      : "/modules/discord/history",
            component : DiscordHistoryComponent,
            name      : "modules_discord_history"
        },
        {
            path      : "/modules/mailing/settings",
            component : MailSettingsComponent,
            name      : "modules_mailing_settings",
        }
    ];

    /**
     * @description returns the vue router
     */
    public getRouter(): Router {
        let vueRouterOptions = {
            routes: this.routes,
            history : createWebHistory(),
        } as RouterOptions;

        let router = createRouter(vueRouterOptions)

        //@ts-ignore
        return router;
    }

}