<template>
    <v-app>
        <Header/>
        <div class="mx-10">
            <v-icon class="float-right ma-1"
                    color="red"
                    @click="deleteVacancy"
                    v-if="vacancy['company_id'] == user['company_id'] || user['permission_id'] > 2">mdi-delete</v-icon>
            <v-card-title>{{ vacancy['vacancy_name']}}</v-card-title>
            <v-card-subtitle v-html="vacancy['vacancy_description'].replace(/(?:\r\n|\r|\n)/g, '<br>')"></v-card-subtitle>
            <v-btn v-if="user['permission_id'] < 2 && this_user_yet_willing_practice == 0" color="success" class="mb-3" small @click="addWillingPracticeUser">Откликнуться</v-btn>
        </div>
        <v-snackbar
            v-model="snackbar_add_willing_practice_user"
        >
            Вы отправили компании информацию о себе!

            <template v-slot:action="{ attrs }">
                <v-btn
                    class="green--text"
                    color="success"
                    text
                    v-bind="attrs"
                    @click="snackbar_add_willing_practice_user = false"
                >
                    OK
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";
import router from "@/router";

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
            this_user_yet_willing_practice: 1,
            requestHasBeenSent: false,
            snackbar_add_willing_practice_user: false,
        };
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user"
        }),
    },
    updated() {
        if(this.user && !this.requestHasBeenSent) {
            this.requestHasBeenSent = true;
            axios.get('/api/getwillingpracticeuser', { params: {
                    'user_id' : this.user['id']
                    }
                })
                .then(response => {
                    console.log(response.data);
                    this.this_user_yet_willing_practice = response.data;
                });
        }
    },
    beforeMount() {
        axios.post('/api/getvacancy', { "id": this.vacancy_id }).then(response => {
                this.vacancy = response.data;
            });
    },
    methods: {
        deleteVacancy() {
            axios.delete('/api/deletevacancy', { params: {"id": this.vacancy_id}} ).then(() => {
                router.replace('/company_'+this.vacancy['company_id']);
            })
        },

        addWillingPracticeUser() {
            axios.post('/api/addwillingpractice', {
                'user_id' : this.user['id'],
                'vacancy_id': this.vacancy_id
            }).then(() => {
                this.this_user_yet_willing_practice = 1;
                this.snackbar_add_willing_practice_user = true;
            });
        }
    },
};
</script>

<style scoped></style>
