<template>
    <v-app>
        <Header />
        <div class="mx-10">
            <v-card outlined>
                <v-btn
                    class="float-right red"
                    small
                    v-if="user['permission_id'] > 2"
                    @click="dialog_delete_company = true"
                    >Удалить компанию</v-btn
                >
                <v-list-item three-line>
                    <v-list-item-action class="text-center">
                        <v-list-item-avatar tile size="200" color="grey">
                            <v-img :src="company['company_image']" />
                        </v-list-item-avatar>
                        <v-btn
                            color="primary"
                            @click.stop="dialog_update_company_image = true"
                            small
                            v-if="user['permission_id'] > 2"
                        >
                            Обновить Логотип компании
                        </v-btn>
                    </v-list-item-action>
                    <v-list-item-content>
                        <v-card-title>{{
                            company["company_name"]
                        }}</v-card-title>
                        <v-card-subtitle
                            v-html="
                                company['company_description'].replace(
                                    /(?:\r\n|\r|\n)/g,
                                    '<br>'
                                )
                            "
                        ></v-card-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-card-actions>
                    <v-btn
                        @click.stop="dialog_add_vacancy = true"
                        v-if="
                            company['id'] == user['company_id'] ||
                                user['permission_id'] > 2
                        "
                        >Добавить стажировку</v-btn
                    >
                    <v-spacer />
                    <v-btn
                        color="secondary"
                        to="/whowantpractice"
                        v-if="company['id'] == user['company_id']"
                        >Желающие пройти практику</v-btn
                    >
                </v-card-actions>
            </v-card>
            <v-dialog v-model="dialog_add_vacancy" max-width="1200">
                <v-card :loading="loading" class="px-3">
                    <v-card-title class="headline">
                        Добавить вакансию
                    </v-card-title>
                    <v-text-field
                        clearable
                        v-model="addvacancy_name_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Заголовок"
                    >
                    </v-text-field>
                    <v-textarea
                        clearable
                        v-model="addvacancy_description_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Описание"
                    >
                    </v-textarea>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_add_vacancy = false"
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
            <v-dialog v-model="dialog_update_company_image" max-width="600">
                <v-card :loading="loading">
                    <v-card-title class="headline"
                        >Загрузить фотографию
                    </v-card-title>
                    <div class="text-center">
                        <input
                            type="file"
                            accept="image/png, image/jpeg"
                            @change="onFileSelected"
                        />
                    </div>
                    <v-card-text class="red--text" v-if="error != null"
                        >{{ error }}
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_update_company_image = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="onUpload(company_id)"
                            :disabled="disabled"
                        >
                            Загрузить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog_delete_company" max-width="600">
                <v-card :loading="loading_delete">
                    <v-card-title class="headline"
                        >Удалить компанию
                    </v-card-title>
                    <v-card-text>
                        Вы действительно хотите удалить эту компанию Вместе с
                        компанией удалятся все пользователи(работадатели)
                        связанные с ней
                    </v-card-text>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_delete_company = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            class="red--text"
                            text
                            @click="deleteCompany"
                            :disabled="disabled_delete"
                        >
                            Удалить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <h2 class="my-5">Стажировки</h2>
            <v-card
                v-for="vacancy in vacancies"
                :key="vacancy.id"
                outlined
                class="my-1"
                :to="'vacancy_' + vacancy.id"
            >
                <v-card-title v-text="vacancy.vacancy_name"></v-card-title>
                <v-card-text
                    v-text="vacancy.vacancy_description.slice(0, 20) + '...'"
                ></v-card-text>
            </v-card>
        </div>
    </v-app>
</template>

<script>
import axios from "axios";
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import store from "@/store";
import router from "@/router";

export default {
    name: "Company",
    components: { Header },
    props: ["company_id"],
    data: () => ({
        addvacancy_name_input: "",
        addvacancy_description_input: "",
        selectedFile: null,
        company: null,
        vacancies: [],
        dialog_update_company_image: false,
        dialog_add_vacancy: false,
        dialog_delete_company: false,
        disabled: false,
        disabled_delete: false,
        loading: false,
        loading_delete: false,
        requestHasBeenSent: false,
        error: ""
    }),
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user"
        })
    },

    mounted() {
        axios.post("/api/company", { id: this.company_id }).then(response => {
            this.company = response.data;
            document.title = this.company["company_name"];
        });
        axios
            .post("/api/getvacancies", { company_id: this.company_id })
            .then(response => {
                this.vacancies = response.data;
            });
    },
    methods: {
        async addVacancy() {
            await axios
                .post("/api/addvacancy", {
                    vacancy_name: this.addvacancy_name_input,
                    vacancy_description: this.addvacancy_description_input,
                    company_id: this.company_id
                })
                .then(resp => {
                    this.dialog_add_vacancy = false;
                    this.addvacancy_name_input = "";
                    this.addvacancy_description_input = "";
                    let newVacancy = resp.data;
                    console.log(newVacancy);
                    this.vacancies.push(newVacancy);
                })
                .catch(() => {
                    //
                });
        },
        deleteCompany() {
            axios
                .delete("/api/deletecompany", {
                    params: { company_id: this.company_id }
                })
                .then(() => {
                    store.dispatch("companies/getcompanies");
                    store.dispatch("users/getusers");
                    router.replace("/companies");
                });
        },

        checkSelectedFile() {
            if (this.selectedFile === null) {
                this.disabled = true;
            } else {
                this.disabled = false;
            }
        },
        onFileSelected(event) {
            this.selectedFile = event.target.files[0];
            this.checkSelectedFile();
        },
        async onUpload(company_id) {
            this.requestHasBeenSent = true;
            this.disabled = true;
            this.loading = true;
            const imgdata = new FormData();
            try {
                imgdata.append(
                    "image",
                    this.selectedFile,
                    this.selectedFile.name
                );
                await axios
                    .post("/api/updatecompanyimage", imgdata, {
                        params: {
                            id: company_id
                        }
                    })
                    .then(response => {
                        this.company["company_image"] = response.data.url;
                        location.reload();
                    })
                    .catch(() => {
                        this.requestHasBeenSent = false;
                        this.disabled = false;
                        this.loading = false;
                        this.error =
                            'Изображение должно иметь расширение "jpg, png, jpeg"';
                    });
            } catch (e) {
                this.selectedFile = null;
                this.disabled = true;
                this.loading = false;
            }
        }
    }
};
</script>

<style scoped></style>
