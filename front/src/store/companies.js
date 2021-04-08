import axios from "axios";

export default {
    namespaced: true,

    state: {
        companies: null,
    },
    getters: {
        companies(state) {
            return state.companies;
        }
    },
    mutations: {
        SET_COMPANIES (state, data) {
            state.companies = data;
        },
    },
    actions: {
        async getcompanies ( { commit } ) {
            try {
                let response = await axios.get("/api/getcompanies");
                commit('SET_COMPANIES', response.data);

            } catch (e) {
                commit('SET_COMPANIES', null);
            }
        },
    }
};
