import Vue from "vue";
import Vuex from "vuex";
import auth from "./auth";
import companies from "@/store/companies";
import users from "@/store/users";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        //
    },
    mutations: {
        //
    },
    actions: {
        //
    },
    modules: {
        auth,
        companies,
        users,
    },
});
