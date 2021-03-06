import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import axios from "axios";
import vueaxios from "vue-axios";

require("@/store/subscriber");

Vue.config.productionTip = false;



if(localStorage.getItem('token')) {
    store.dispatch("auth/attempt", localStorage.getItem("token"));
    store.dispatch('companies/getcompanies');
    store.dispatch('users/getusers');
    store.dispatch('users/getGroups');
    store.dispatch('users/getAny');
} else {
    if(location.pathname !== '/signin' && location.pathname !== '/welcomepage' ) {
        location.replace("/welcomepage")
    }
}


Vue.use(vueaxios, axios);

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount("#app");
