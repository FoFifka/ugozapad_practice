<template>
    <v-container fluid>
        <Header />
        <v-layout column>
            <v-card
                class="mx-10"
                outlined
            >
                <v-list-item three-line>
                    <v-flex class="wrap row">
                        <div>
                            <v-list-item-avatar
                                color="grey"
                                size="200"
                                tile
                            >
                                <v-img :src="this_user['avatar']" />
                            </v-list-item-avatar>
                            <p v-if="user['id'] == user_id || user['permission_id'] > 3"
                               class="text-center justify-center">
                                <v-btn color="primary" @click.stop="dialog_change_image = true">
                                    Обновить Аватар
                                </v-btn>
                            </p>
                        </div>
                        <v-list-item-content style="min-width: 200px">
                            <v-card-text v-if="this_user['name'] !== ''" label="Имя">{{
                                    this_user["name"]
                                }}
                            </v-card-text>
                            <v-card-text v-if="this_user['surname']  !== ''" label="Фамилия">{{
                                    this_user["surname"]
                                }}
                            </v-card-text>
                            <v-card-text
                                v-if="this_user['patronymic'] != null && this_user['patronymic'] != ''"
                                label="Отчество"
                            >{{ this_user["patronymic"] }}
                            </v-card-text
                            >
                            <v-card-text v-if="this_user['group'] != '' && this_user['group'] != null" label="Группа">{{
                                    this_user["group"]
                                }}
                            </v-card-text>
                            <v-card-text v-if="this_user['permission'] !== ''" label="Группа">{{ this_user["permission"]
                                }}
                            </v-card-text>
                            <v-card-text v-if="this_user['companies_id'] !== null"
                                         label="Группа">
                                {{ this_user["company"] }}
                            </v-card-text>
                        </v-list-item-content>
                    </v-flex>
                </v-list-item>
            </v-card>
            <h2 v-if="this_user['permission_id'] < 2" class="mx-10">Моя средняя оценка
                <v-btn v-if="user['id'] == user_id || user['permission_id'] > 3" small @click="dialog_add_mark = true">
                    <v-icon dark>
                        {{ this_user["mark_id"] == null ? "mdi-plus-circle-outline" : "mdi-pencil-circle-outline" }}
                    </v-icon>
                    {{ this_user["mark_id"] == null ? "Указать" : "Изменить" }}
                </v-btn>
            </h2>
            <p v-if="this_user['permission_id'] < 2" class="mx-10">{{ this_user["mark"] }}</p>
            <h2 v-if="this_user['permission_id'] < 2" class="mx-10">Обо мне
                <v-btn v-if="this_user['about_me'] == null && (user['id'] == user_id  || user['permission_id'] > 3)" small
                       @click="dialog_add_aboutme = true">
                    <v-icon dark>mdi-plus-circle-outline</v-icon>
                    Добавить
                </v-btn>
                <v-btn v-if="this_user['about_me'] != null && (user['id'] == user_id  || user['permission_id'] > 3)" small
                       @click="openDialogChangeAboutMe">
                    <v-icon dark>mdi-pencil-circle-outline</v-icon>
                    Изменить
                </v-btn>
            </h2>
            <p v-if="(this_user['about_me'] == null && this_user['about_me'] == '') && this_user['permission_id'] < 2" class="mx-10">Вы пока ничего не написали о себе
            </p>
            <p v-if="this_user['about_me'] != null && this_user['about_me'] != ''" class="mx-10">
                {{ this_user['about_me'] }}
            </p>
            <v-dialog v-model="dialog_change_image" max-width="600">
                <v-card :loading="loading">
                    <v-card-title class="headline"
                    >Загрузить фотографию
                    </v-card-title
                    >
                    <div class="text-center">
                        <input
                            accept="image/png, image/jpeg"
                            type="file"
                            @change="onFileSelected"
                        />
                    </div>
                    <v-card-text
                        v-if="error != null"
                        class="red--text"
                    >{{ error }}
                    </v-card-text
                    >
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_change_image = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            :disabled="disabled"
                            color="primary"
                            text
                            @click="onUpload"
                        >
                            Загрузить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog_add_mark" max-width="600">
                <v-card>
                    <v-card-title class="headline"
                    >{{ this_user["mark_id"] == null ? "Указать" : "Изменить" }} среднюю оценку
                    </v-card-title
                    >
                    <v-radio-group
                        v-model="selected_radioButton"
                        class="mx-5"
                        mandatory>
                        <v-radio
                            v-for="mark in marks"
                            :key="mark"
                            :label="`${mark['mark']}`"
                            :value="mark"
                        ></v-radio>
                    </v-radio-group>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_add_mark = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="addMark"
                        >
                            Принять
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog_add_aboutme" max-width="600">
                <v-card>
                    <v-card-title class="headline"
                    >Создать резюме
                    </v-card-title>
                    <v-textarea class="mx-5" v-model="input_aboutme" label="Напишите о себе">

                    </v-textarea>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_add_aboutme = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="addAboutMe"
                            :disabled="btn_add_aboutme_disable"
                        >
                            Принять
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog_change_aboutme" max-width="600">
                <v-card>
                    <v-card-title class="headline"
                    >Изменить резюме
                    </v-card-title>
                    <v-textarea class="mx-5" v-model="input_aboutme" label="Напишите о себе">

                    </v-textarea>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_change_aboutme = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            :disabled="btn_add_aboutme_disable"
                            @click="addAboutMe"
                        >
                            Принять
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
    </v-container>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
    name: "AnotherUserProfile",
    props: ["user_id"],
    data() {
        return {
            this_user: null,
            dialog_change_image: false,
            dialog_add_mark: false,
            dialog_add_aboutme: false,
            dialog_change_aboutme: false,
            selectedFile: null,
            requestHasBeenSent: false,
            error: null,
            disabled: false,
            btn_add_aboutme_disable: false,
            loading: false,
            company_name: "",
            //this_user_resumes: [],
            selected_radioButton: 0,
            input_aboutme: ""
        };
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user",
            marks: "users/marks"
        })
    },
    mounted() {
        this.getUser();
        //this.getResumes();
    },
    updated() {
        this.checkSelectedFile();

        if(this.input_aboutme == "") {
            this.btn_add_aboutme_disable = true
        } else {
            this.btn_add_aboutme_disable = false
        }
    },
    methods: {
        async getUser() {
            await axios.get("/api/getuser", { params: { "id": this.user_id } }).then(response => {
                this.this_user = response.data;
                if (this.this_user["mark_id"] != null) {
                    axios.get("/api/getmark", { params: { "id": this.this_user["mark_id"] } }).then(response => {
                        this.this_user["mark"] = response.data["mark"];
                    });
                }
            });
        },

        // getResumes() {
        //     axios.get("/api/getresumes", { params: { "id": this.user_id } }).then(response => {
        //         if (response.data != "") {
        //             this.this_user_resumes = response.data;
        //         }
        //     });
        // },
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
        async onUpload() {
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
                    .post("/api/updateuserimage", imgdata, {
                        params: { "id": this.user_id }
                    })
                    .then(response => {
                        this.error = null;
                        this.dialog_change_image = false;
                        this.loading = false;
                        this.this_user.avatar = response.data.url;
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
        addMark() {
            if(this.selected_radioButton["id"] == this.this_user["mark_id"]) {
                this.dialog_add_mark = false;
            } else {
                axios.post("/api/changemark", {
                    "id": this.this_user["id"],
                    "mark_id": this.selected_radioButton["id"]
                }).then(() => {
                    this.this_user['mark_id'] = this.selected_radioButton['id'];
                    this.this_user['mark'] = this.selected_radioButton['mark'];
                    this.dialog_add_mark = false;
                });
            }

        },
        addAboutMe() {
            if(this.this_user['about_me'] == this.input_aboutme) {
                this.dialog_add_aboutme = false;
                this.dialog_change_aboutme = false;
            } else {
                axios.put("/api/changeuseraboutme", {
                    "id": this.this_user["id"],
                    "about_me": this.input_aboutme
                }).then(response => {
                    this.this_user['about_me'] = response.data
                    this.dialog_add_aboutme = false;
                    this.dialog_change_aboutme = false;
                    this.input_aboutme = "";

                });
            }
        },
        deleteResume() {
            axios.delete('/api/deleteresume', {

            }).then()
        },
        openDialogChangeAboutMe() {
            this.dialog_change_aboutme = true;
            this.input_aboutme = this.this_user['about_me'];
        }
    }
};
</script>

<style scoped></style>
