import axios from "axios";


export default {
    namespaced: true,

    state: {
        token: null,
        user: null,
        resumes: null,
    },
    getters: {
        authenticated(state) {
            return state.token && state.user;
        },

        user(state) {
            return state.user;
        },
        resumes(state) {
            return state.resumes;
        }
    },
    mutations: {
        SET_TOKEN (state, token) {
            state.token = token;
        },
        SET_USER (state, data) {
            state.user = data;
        },
        SET_RESUMES(state, data) {
            state.resumes = data
        }
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

                let resumes = await  axios.get("/api/getresumes", {params: { "id": response2.data['id']}});
                commit('SET_RESUMES', resumes.data);

                if(location.pathname === '/signin') {
                    location.replace("/")
                }

            } catch (e) {
                commit('SET_TOKEN', null);
                commit('SET_USER', null);
                commit('SET_MARK', null);
                commit('SET_RESUMES', null);

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
