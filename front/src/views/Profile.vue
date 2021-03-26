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
                                <v-img :src="user['avatar']"/>
                            </v-list-item-avatar>
                            <p class="text-center justify-center">
                                <v-btn color="primary" @click.stop="dialog = true">
                                    Обновить Аватар
                                </v-btn>
                            </p>
                        </div>
                        <v-list-item-content style="min-width: 200px">
                            <v-card-text v-if="user.name !== ''" label="Имя">{{
                                    user['name']
                                }}</v-card-text>
                            <v-card-text v-if="user.surname !== ''" label="Фамилия">{{
                                    user['surname']
                                }}</v-card-text>
                            <v-card-text
                                v-if="user['patronymic'] !== ''"
                                label="Отчество"
                            >{{ user['patronymic'] }}</v-card-text
                            >
                            <v-card-text v-if="user['group'] !== ''" label="Группа">{{
                                    user['group']
                                }}</v-card-text>
                            <v-card-text v-if="user['permission'] !== ''" label="Группа">{{
                                    user['permission'] == 1
                                        ? "Студент"
                                        : user['permission'] == 2
                                        ? "Работадатель"
                                        : user['permission'] == 3
                                            ? "Учитель"
                                            : user['permission'] == 4
                                                ? "Админ"
                                                : ""
                                }}</v-card-text>
                        </v-list-item-content>
                    </v-flex>
                </v-list-item>
            </v-card>
            <v-dialog v-model="dialog" max-width="600">
                <v-card :loading="loading">
                    <v-card-title class="headline"
                    >Загрузить фотографию</v-card-title
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
                    >{{ error }}</v-card-text
                    >
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
                            @click="onUpload"
                            :disabled="disabled"
                        >
                            Загрузить
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
            dialog: false,
            selectedFile: null,
            requestHasBeenSent: false,
            error: null,
            disabled: false,
            loading: false
        };
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user"
        })
    },
    updated() {
        this.checkSelectedFile();
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
                        headers: {
                            Authorization:
                                "Bearer " + localStorage.getItem("token")
                        }
                    })
                    .then(response => {
                        this.error = null;
                        this.dialog = false;
                        this.loading = false;
                        this.user.avatar = response.data.url;
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
