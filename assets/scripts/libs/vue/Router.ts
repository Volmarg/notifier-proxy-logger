import {createRouter, createWebHistory, RouterOptions} from 'vue-router';

/**
 * @description Router used by vue
 */
export default class Router {
    readonly Component1 = { template: '<div>Route</div>' };
    readonly Component2 = { template: '<div>Route2</div>' };

    /**
     * @description Definitions of vue routes
     */
    readonly routes : Array<Object> = [
        {
            path      : '/modules/mailing/overview',
            component : this.Component1,
            name      : "modules_mailing_overview"

        },
        {
            path      : '/modules/dashboard/overview',
            component : this.Component2,
            name      : "modules_dashboard_overview"
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

        var router = createRouter(vueRouterOptions)

        //@ts-ignore
        return router;
    }

}