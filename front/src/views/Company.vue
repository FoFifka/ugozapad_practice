<template>
    <v-app>
        <Header/>
        <div class="mx-10">
            <v-card
                outlined
            >
                <v-btn class="float-right red" small v-if="user['permission_id'] > 3" @click="dialog_delete_company = true">Удалить компанию</v-btn>
                <v-list-item three-line>
                    <div>
                        <v-list-item-avatar
                            tile
                            size="200"
                            color="grey"
                        >
                            <v-img :src="company['company_image']" />
                        </v-list-item-avatar>
                        <p class="text-center justify-center" v-if="user['permission_id'] > 3">
                            <v-btn color="primary" @click.stop="dialog_update_company_image = true">
                                Обновить Логотип компании
                            </v-btn>
                        </p>
                    </div>
                    <v-list-item-content>
                        <v-card-title>{{ company['company_name'] }}</v-card-title>
                        <v-card-subtitle>{{ company['company_description'] }}</v-card-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-card-actions v-if="company['id'] == user['companies_id'] || user['permission_id'] > 3">
                    <v-btn @click.stop="dialog_add_vacancy = true">Добавить стажировку</v-btn>
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
                        label="Заголовок">
                    </v-text-field>
                    <v-textarea
                        clearable
                        v-model="addvacancy_description_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Описание">
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
                    </v-card-title
                    >
                    <div class="text-center">
                        <input
                            type="file"
                            accept="image/png, image/jpeg"
                            @change="onFileSelected"
                        />
                    </div>
                    <v-card-text
                        class="red--text"
                        v-if="error != null"
                    >{{ error }}
                    </v-card-text
                    >
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
                <v-card :loading="loading">
                    <v-card-title class="headline"
                    >Удалить компанию
                    </v-card-title
                    >
                    <v-card-text>
                        Вы действительно хотите удалить эту компанию?
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
                            :disabled="disabled"
                        >
                            Удалить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <h2 class="my-5">Стажировки</h2>
            <v-card
                v-for="vacancy in vacancies"
                :key="vacancy"
                outlined
                class="my-1"
                :to="'vacancy_'+vacancy['id']"
            >
                <v-card-title v-text="vacancy['vacancy_name']"></v-card-title>
                <v-card-text v-text="vacancy['vacancy_description']"></v-card-text>
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
            selectedFile: null,
            company: null,
            vacancies: null,
            dialog_update_company_image: false,
            dialog_add_vacancy: false,
            dialog_delete_company: false,
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
    mounted() {
        axios.post('/api/company', { id: this.company_id}).then(response => {
            this.company = response.data;
            document.title = this.company['company_name'];
        });
        axios.post('/api/getvacancies', { 'companies_id': this.company_id}).then(response => {
            this.vacancies = response.data;

        });
    },
    methods: {
        addVacancy() {
            console.log(this.addvacancy_name_input+" | "+this.addvacancy_description_input+" | "+this.company_id);
            axios.post('/api/addvacancy', {
                'vacancy_name': this.addvacancy_name_input,
                'vacancy_description': this.addvacancy_description_input,
                'companies_id': this.company_id
            }).then(() => {
                location.reload();
            }).catch(error => {
                console.log(error);
            });
        },
        deleteCompany() {
            axios.delete('/api/deletecompany', { params: { 'companies_id': this.company_id}}).then(() => {
                location.replace('/companies');
            })
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
            console.log(company_id);

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
                            "id": company_id
                        }
                    })
                    .then(response => {
                        this.company['company_image'] = response.data.url;
                        location.reload();
                    })
                    .catch(() => {
                        this.requestHasBeenSent = false;
                        this.disabled = false;
                        this.loading = false;
                        this.error =
                            "Изображение должно иметь расширение \"jpg, png, jpeg\"";
                    });
            } catch (e) {
                this.selectedFile = null;
                this.disabled = true;
                this.loading = false;
            }
        },
    }
};
</script>

<style scoped></style>
