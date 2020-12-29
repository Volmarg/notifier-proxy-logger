import {createRouter, createWebHistory, RouterOptions}  from 'vue-router';
import MailingModuleOverviewComponent                   from '../../vue-components/pages/modules/mailing/overview.vue';
import MailingModuleHistoryComponent                    from '../../vue-components/pages/modules/mailing/mailing-history.vue';
import DashboardOverviewComponent                       from '../../vue-components/pages/modules/dashboard/overview.vue';
import DiscordWebhookManagementComponent                from '../../vue-components/pages/modules/discord/discord-manage-webhooks.vue';

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