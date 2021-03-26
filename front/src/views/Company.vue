<template>
    <v-app>
        <Header/>
        <div class="mx-10">
            <v-card
                outlined
            >
                <v-list-item three-line>
                    <div>
                        <v-list-item-avatar
                            tile
                            size="200"
                            color="grey"
                        >
                            <img :src="company['company_image']" />
                        </v-list-item-avatar>
                    </div>
                    <v-list-item-content>
                        <v-card-title>{{ company['company_name'] }}</v-card-title>
                        <v-card-subtitle>{{ company['company_description'] }}</v-card-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-card-actions v-if="company['id'] == user['companies_id']">
                    <v-btn @click.stop="dialog = true">Добавить вакансию</v-btn>
                </v-card-actions>
            </v-card>
            <v-dialog v-model="dialog" max-width="1200">
                <v-card :loading="loading" class="px-3">
                    <v-card-title class="headline">
                        Добавить вакансию
                    </v-card-title>
                    <v-text-field
                        clearable
                        v-model="addvacancy_name_input"
                        clear-icon="mdi-close-circle"
                        label="Заголовок"></v-text-field>
                    <v-textarea
                        clearable
                        v-model="addvacancy_description_input"
                        clear-icon="mdi-close-circle"
                        label="Описание">
                    </v-textarea>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            :disabled="disabled"
                            @click="addVacancy"
                        >
                            Добавить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <h2 class="my-5">Вакансии</h2>
            <v-card
                v-for="vacancy in vacancies"
                :key="vacancy"
                outlined
                class="my-1"
                :to="'vacancy_'+vacancy['id']"
            >
                <v-card-title v-text="vacancy['name']"></v-card-title>
                <v-card-text v-text="vacancy['description']"></v-card-text>
            </v-card>
        </div>
    </v-app>
</template>

<script>
import axios from "axios";
import Header from "@/components/Header";
import { mapGetters } from "vuex";

export default {
    name: "Company",
    components: { Header },
    props: ['company_id'],
    data: function() {
        return {
            addvacancy_name_input: "",
            addvacancy_description_input: "",
            company: null,
            vacancies: null,
            dialog: false,
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
        axios.post('/api/company', { id: this.company_id}).then(response => {
            this.company = response.data;
            document.title = this.company['company_name'];
            console.log(response.data);
        });
        axios.post('/api/getvacancies', { 'companies_id': this.company_id}).then(response => {
            this.vacancies = response.data;
            console.log(this.vacancies.length);

        });
    },
    methods: {
        addVacancy() {
            axios.post('/api/addvacancy', {
                'name': this.addvacancy_name_input,
                'description': this.addvacancy_description_input,
                'companies_id': this.company_id
            }).then(response => {
                console.log(response.data);
            })
        }
    }
};
</script>

<style scoped></style>
