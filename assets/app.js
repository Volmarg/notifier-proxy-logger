/**
 * @description this file contains the logic to start the Vue in the project
 */

/* Components */
import Main     from './scripts/vue-components/base-layout/main';
import Sidebar  from './scripts/vue-components/base-layout/components/sidebar/sidebar';
import Router   from './scripts/libs/vue/Router';
import VueAxios from "vue-axios";
import axios    from 'axios';

var router = new Router();
var Vue    = require('vue');

const app = Vue.createApp({
    template: `
      <Sidebar/>
      <Main/>
    `,
    components: {
        Sidebar,
        Main
    }
})

// add plugins
app.use(router.getRouter());
app.use(VueAxios, axios);

// Mount the main app to the DOM
app.mount('#app');