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
                                tile
                                size="200"
                                color="grey"
                            >
                                <v-img :src="user['avatar']" />
                            </v-list-item-avatar>
                            <p class="text-center justify-center">
                                <v-btn color="primary" @click.stop="dialog_change_image = true">
                                    Обновить Аватар
                                </v-btn>
                            </p>
                        </div>
                        <v-list-item-content style="min-width: 200px">
                            <v-card-text v-if="user.name !== ''" label="Имя">{{
                                    user["name"]
                                }}
                            </v-card-text>
                            <v-card-text v-if="user.surname !== ''" label="Фамилия">{{
                                    user["surname"]
                                }}
                            </v-card-text>
                            <v-card-text
                                v-if="user['patronymic'] != null && user['patronymic'] != ''"
                                label="Отчество"
                            >{{ user["patronymic"] }}
                            </v-card-text
                            >
                            <v-card-text v-if="user['group'] != '' && user['group'] != null" label="Группа">{{
                                    user["group"]
                                }}
                            </v-card-text>
                            <v-card-text v-if="user['permission'] !== ''" label="Роль">
                                {{ user['permission'] }}
                            </v-card-text>
                            <v-card-text v-if="user['companies_id'] !== '' || user['companies_id'] !== null"
                                         label="Компания">
                                {{ user['company'] }}
                            </v-card-text>
                        </v-list-item-content>
                    </v-flex>
                </v-list-item>
            </v-card>
            <h2 class="mx-10" v-if="user['permission_id'] < 2">Моя средняя оценка <v-btn small @click="dialog_add_mark = true"><v-icon dark>{{ user['mark_id'] == null ? "mdi-plus-circle-outline" : "mdi-pencil-circle-outline"}}</v-icon>{{ user['mark_id'] == null ? "Указать" : "Изменить"}}</v-btn></h2>
            <p class="mx-10" v-if="user['permission_id'] < 2">{{ user['mark'] == null ? "Оценки пока нет" : user['mark'] }}</p>
            <h2 v-if="user['permission_id'] < 2" class="mx-10">Обо мне
                <v-btn v-if="user['about_me'] == null || user['permission_id'] > 2" small
                       @click="dialog_add_aboutme = true">
                    <v-icon dark>mdi-plus-circle-outline</v-icon>
                    Добавить
                </v-btn>
                <v-btn v-if="user['about_me'] != null || user['permission_id'] > 2" small
                       @click="openDialogChangeAboutMe">
                    <v-icon dark>mdi-pencil-circle-outline</v-icon>
                    Изменить
                </v-btn>
            </h2>
            <p v-if="(user['about_me'] == null && user['about_me'] == '') && user['permission_id'] < 2" class="mx-10">Вы пока ничего не написали о себе
            </p>
            <p v-if="user['about_me'] != null && user['about_me'] != ''" class="mx-10" v-html="user['about_me'].replace(/(?:\r\n|\r|\n)/g, '<br>')"></p>
            <v-dialog v-model="dialog_change_image" max-width="600">
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
                            @click="dialog_change_image = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            @click="onUpload(user['id'])"
                            :disabled="disabled"
                        >
                            Загрузить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialog_add_mark" max-width="600">
                <v-card>
                    <v-card-title class="headline"
                    >{{ user['mark_id'] == null ? "Указать" : "Изменить"}}
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
    name: "Profile",
    data() {
        return {
            selected_radioButton: 0,
            dialog_change_image: false,
            dialog_add_mark: false,
            dialog_add_aboutme: false,
            dialog_change_aboutme: false,
            selectedFile: null,
            error: null,
            disabled: false,
            loading: false,
            company_name: "",
            user_permission_id: "",
            input_aboutme: "",
            btn_add_aboutme_disable: false,
        };
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user",
            marks: "users/marks",
        }),

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
        async onUpload(user_id) {
            console.log(user_id);
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
                        params: {
                            "id": user_id
                        }
                    })
                    .then(response => {
                        this.error = null;
                        this.dialog_change_image = false;
                        this.loading = false;
                        this.user.avatar = response.data.url;
                    })
                    .catch(() => {
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
            if(this.selected_radioButton["id"] == this.user["mark_id"]) {
                this.dialog_add_mark = false;
            } else {
                axios.post('/api/changemark', {
                    'id': this.user['id'],
                    'mark_id': this.selected_radioButton['id']
                }).then(() => {
                    this.user['mark_id'] = this.selected_radioButton['id'];
                    this.user['mark'] = this.selected_radioButton['mark'];
                    this.dialog_add_mark = false;
                });
            }
        },
        addAboutMe() {
            if(this.user['about_me'] == this.input_aboutme) {
                this.dialog_add_aboutme = false;
                this.dialog_change_aboutme = false;
            } else {
                axios.put("/api/changeuseraboutme", {
                    "id": this.user["id"],
                    "about_me": this.input_aboutme
                }).then(response => {
                    this.user['about_me'] = response.data
                    this.dialog_add_aboutme = false;
                    this.dialog_change_aboutme = false;
                    this.input_aboutme = "";

                });
            }
        },
        openDialogChangeAboutMe() {
            this.dialog_change_aboutme = true;
            this.input_aboutme = this.user['about_me'];
        },
    }
};
</script>

<style scoped></style>
