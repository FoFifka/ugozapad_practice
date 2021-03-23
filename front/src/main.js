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
} else {
    if(location.pathname !== '/signin') {
        location.replace("/signin")
    }
}


Vue.use(vueaxios, axios);

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount("#app");
