window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import {createApp} from 'vue'

import Table from './Table.vue'

createApp(Table).mount("#app")