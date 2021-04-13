import axios from "axios";


export default {
    namespaced: true,

    state: {
        token: null,
        user: null,
        willingPractice: null,
    },
    getters: {
        authenticated(state) {
            return state.token && state.user;
        },

        user(state) {
            return state.user;
        },
        willingPracticeUsers(state) {
            return state.willingPractice;
        },
    },
    mutations: {
        SET_TOKEN (state, token) {
            state.token = token;
        },
        SET_USER (state, data) {
            state.user = data;
        },
        SET_WILLING_PRACTICE(state, data) {
            state.willingPractice = data;
        },
    },
    actions: {
        async signIn({ dispatch }, credentials) {
            let response = await axios.post('api/signin', credentials);
            dispatch("attempt", response.data.token);
        },
        async attempt({ commit }, token) {
            commit("SET_TOKEN", token);

            try {
                let response2 = await axios.get('api/user');
                commit('SET_USER', response2.data);


                if(response2.data['companies_id'] != null) {
                    let willingPractice = await axios.get('/api/getwillingpractice', { params: { 'company_id': response2.data['companies_id'] }});

                    commit("SET_WILLING_PRACTICE", willingPractice.data);
                }

                if(location.pathname === '/signin') {
                    location.replace("/")
                }
            } catch (e) {
                commit('SET_TOKEN', null);
                commit('SET_USER', null);
                commit('SET_MARK', null);
                commit('SET_WILLING_PRACTICE', null);

                if(location.pathname !== '/signin') {
                    location.replace("/signin");
                }
            }
        },

        logOut({ commit }) {
            return axios({
                method: 'post',
                url: '/api/logout',
                headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('token')
                }
            }).then(() => {
                commit("SET_TOKEN", null);
                commit("SET_USER", null);
            });
        }
    }
};
