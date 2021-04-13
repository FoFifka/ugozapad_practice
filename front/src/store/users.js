import axios from "axios";

export default {
    namespaced: true,

    state: {
        users: null,
        students: null,
        permissions: null,
        marks: null,
        groups: null,
        genders: null,
    },
    getters: {
        users(state) {
            return state.users;
        },
        students(state) {
            return state.students;
        },
        permissions(state) {
            return state.permissions;
        },
        marks(state) {
            return state.marks;
        },
        groups(state) {
            return state.groups;
        },
        genders(state) {
            return state.genders;
        }
    },
    mutations: {
        SET_USERS (state, data) {
            state.users = data;
        },
        SET_STUDENTS (state, data ){
          state.students = data;
        },
        SET_PERMISSIONS (state, data) {
            state.permissions = data;
        },
        SET_MARKS(state, data) {
            state.marks = data;
        },
        SET_GROUPS(state, data) {
            state.groups = data;
        },
        SET_GENDERS(state, data) {
            state.genders = data;
        }
    },
    actions: {
        async getusers ( { commit } ) {
            try {
                let response = await axios.get('/api/getusers');
                commit('SET_USERS', response.data);

                let response2 = await axios.get("/api/getstudents");
                commit('SET_STUDENTS', response2.data);

                let response3 = await axios.get('/api/getpermissions');
                commit('SET_PERMISSIONS', response3.data);

                let response4 = await axios.get("/api/getmarks");
                commit('SET_MARKS', response4.data);

                let response5 = await axios.get('/api/getgroups');
                commit('SET_GROUPS', response5.data);

                let response6 = await axios.get('/api/getgenders');
                commit('SET_GENDERS', response6.data);
            } catch (e) {
                commit('SET_USERS', null);
                commit('SET_STUDENTS', null);
                commit('SET_PERMISSIONS', null);
                commit('SET_MARKS', null);
                commit('SET_GROUPS', null);
                commit('SET_GENDERS', null);
            }
        },
    }
};
