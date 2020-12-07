/**
 * @description this file contains the logic to start the Vue in the project
 */
// todo: check what's the difference * vs Names from default

/* Components */
import Main     from './scripts/vue-components/base-layout/main';
import Sidebar  from './scripts/vue-components/base-layout/components/sidebar/sidebar';

var Vue = require('vue');

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

app.mount('#app')