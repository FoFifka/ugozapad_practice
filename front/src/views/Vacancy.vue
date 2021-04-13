<template>
    <v-app>
        <Header/>
        <div class="mx-10">
            <v-card>
                <v-icon class="float-right ma-1"
                        color="red"
                        @click="deleteVacancy"
                        v-if="vacancy['companies_id'] == user['companies_id'] || user['permission_id'] > 2">mdi-delete</v-icon>
                <v-card-title>{{ vacancy['vacancy_name']}}</v-card-title>
                <v-card-subtitle v-html="vacancy['vacancy_description'].replace(/(?:\r\n|\r|\n)/g, '<br>')"></v-card-subtitle>
            </v-card>
        </div>
    </v-app>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
    name: "Vacancy",
    components: { Header },
    props: ['vacancy_id'],
    data: function() {
        return {
            vacancy: null,
            vacancies: null,
            disabled: false,
            loading: false,
        };
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user"
        }),
    },
    beforeMount() {
        axios.post('/api/getvacancy', { "id": this.vacancy_id }).then(response => {
            this.vacancy = response.data;
        })
    },
    methods: {
        deleteVacancy() {
            axios.delete('/api/deletevacancy', { params: {"id": this.vacancy_id}} ).then(response => {
                location.replace('/company_'+this.vacancy['companies_id']);
            })
        }
    },
};
</script>

<style scoped></style>
