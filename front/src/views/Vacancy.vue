<template>
    <v-app>
        <Header/>
        <div class="mx-10">
            <v-card>
                <v-icon class="float-right ma-1"
                        color="red"
                        @click="deleteVacancy"
                        v-if="vacancy['companies_id'] == user['companies_id']">mdi-delete</v-icon>
                <v-card-title>{{ vacancy['name']}}</v-card-title>
                <v-card-subtitle>{{ vacancy['description']}}</v-card-subtitle>
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
        console.log(this.vacancy_id);
        axios.post('/api/getvacancy', { "id": this.vacancy_id }).then(response => {
            this.vacancy = response.data;
            console.log(response.data);
        })
    },
    methods: {
        deleteVacancy() {
            axios.delete('/api/deletevacancy', { params: {"id": this.vacancy_id}} ).then(response => {
                console.log(response.data);
                location.replace('/company_'+this.vacancy['companies_id']);
            })
        }
    },
};
</script>

<style scoped></style>
