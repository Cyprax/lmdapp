require('./bootstrap');

import Vue from 'vue';


//Modules

//FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core';

import {
    faInfoCircle
} from '@fortawesome/free-solid-svg-icons'

library.add(
    faInfoCircle
)
/*import {
    faTachometerAlt,
    faBars,
    faUserCircle,
    faIdCard,
    faBell,
    faPowerOff,
    faChevronCircleLeft,
    faChevronCircleRight,
    faReplyAll,
    faRedoAlt,
    faEye,
    faEyeSlash,
    faSearch,
    faTimes,
    faTimesCircle,
    faPlus,
    faEraser,
    faPen,
    faPenAlt,
    faArrowDown,
    faChevronDown,
    faDownload,
    faUpload,
    faCheck,
    faMinus
} from '@fortawesome/free-solid-svg-icons'
library.add(
    faTachometerAlt, faBars, faUserCircle, faIdCard, faBell, faInfoCircle, faPowerOff,
    faChevronCircleLeft, faChevronCircleRight, faRedoAlt, faReplyAll, faEye, faEyeSlash,
    faSearch, faTimes, faTimesCircle, faPlus, faEraser, faPen, faPenAlt,
    faArrowDown, faChevronDown, faDownload, faUpload, faCheck, faMinus
)*/

import {
    FontAwesomeIcon
} from '@fortawesome/vue-fontawesome'

Vue.component('font-awesome-icon', FontAwesomeIcon)



//BootstrapVue && Bootstrap
import BootstrapVue from 'bootstrap-vue'
//import "bootswatch/dist/united/bootstrap.css";
import "bootstrap/dist/css/bootstrap.css";
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)


//Buefy
/*import Buefy from 'buefy'
import 'buefy/dist/buefy.css'
Vue.use(Buefy)
*/

//Router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

import { routes } from './routes/routes';
const router = new VueRouter({
    routes,
    mode: 'history'
});


//Store
import Vuex from 'vuex';
Vue.use(Vuex);

//import StoreData from './store';
//const store = new Vuex.Store(StoreData);
import authModule from './store/authModule';
import windowModule from './store/windowModule';
import datasModule from './store/datasModule';

const store = new Vuex.Store({
    modules: {
        auth: authModule,
        window: windowModule,
        datas: datasModule
    },
    //StoreData
});


//Initialization
import { initialize } from './utils/initialize';
initialize(store, router);

import App from './App.vue';
new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app')
