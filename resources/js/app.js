import Vue from "vue";
import router from "./router";
import App from "./App.vue";
import store from "./store.js";
// import axios from "axios";
import VueAxios from "vue-axios";
import VueRouter from "vue-router";

import bootstrap from "./bootstrap";

import { library } from '@fortawesome/fontawesome-svg-core'
import { faPencilAlt, faTrashAlt, faPlus, faSearch, faSignInAlt, faSignOutAlt, faCog, faTimes, faTrashRestoreAlt, faUsers, faUpload, faFile, faFileAudio, faFileVideo, faFileImage, } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon, FontAwesomeLayers } from '@fortawesome/vue-fontawesome'

 
library.add([faPencilAlt, faTrashAlt, faPlus, faSearch, faSignInAlt, faSignOutAlt, faCog, faTimes, faTrashRestoreAlt, faUsers, faUpload, faFile, faFileAudio, faFileVideo, faFileImage]);
 
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('font-awesome-layers', FontAwesomeLayers)
 
Vue.config.productionTip = false

window.Vue = Vue;
// window.axios = axios;
Vue.prototype.axios = axios;
Vue.router = router;
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

axios.defaults.baseURL = "/api";

const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        App,
    }
});