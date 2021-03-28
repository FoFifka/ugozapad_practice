<template>
    <v-container fluid>
        <Header />
        <v-btn v-if="user['permission'] > 3" @click="dialog_add_company = true">Добавить компанию</v-btn>
        <h1 class="text-center my-2">Компании с которыми у нас контракт</h1>
        <v-flex class="wrap row mx-auto justify-center" align-self-center>
            <v-card
                width="350"
                outlined
                class="ma-1"
                v-for="company in companies"
                :key="company.title"
                :to="'/company_'+company['id']"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            {{ company['company_name'] }}
                        </v-list-item-title>
                        <v-list-item-subtitle>{{
                                company['company_description']
                        }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar tile size="80" color="primary">
                        <v-img :src="company['company_image']"></v-img>
                    </v-list-item-avatar>
                </v-list-item>
            </v-card>
        </v-flex>
        <v-dialog v-model="dialog_add_company" max-width="1200">
            <v-card :loading="loading" class="px-3">
                <v-card-title class="headline">
                    Добавить вакансию
                </v-card-title>
                <v-text-field
                    clearable
                    v-model="addcompany_name_input"
                    clear-icon="mdi-close-circle"
                    type="text"
                    label="Название компании">
                </v-text-field>
                <v-textarea
                    clearable
                    v-model="addcompany_description_input"
                    clear-icon="mdi-close-circle"
                    type="text"
                    label="Описание">
                </v-textarea>
                <v-card-actions>
                    <v-btn
                        color="primary"
                        text
                        @click="dialog_add_company = false"
                    >
                        Отмена
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="primary"
                        text
                        :disabled="disabled"
                        @click="addCompany"
                    >
                        Добавить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
    name: "Companies",
    data: () => ({
        addcompany_name_input: "",
        addcompany_description_input: "",
        dialog_add_company: false,
        loading: false,
        disabled: false,
        companies: null
    }),
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user"
        })
    },
    beforeMount() {
        axios
            .get("/api/getcompanies")
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        addCompany() {
            axios.post('/api/addcompany', {
                'company_name': this.addcompany_name_input,
                'company_description': this.addcompany_description_input,
            }).then(() => {
                location.reload();
            }).catch(error => {
                console.log(error);
            })
        }
    },
};
</script>

<style scoped></style>
