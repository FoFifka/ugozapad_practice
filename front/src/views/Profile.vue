<template>

    <v-container fluid>
        <Header/>
        <v-layout column>
            <v-card>
                <v-card-text>
                    <v-flex class="mb-4">
                        <v-avatar size="100" class="mr-4">
                            <img :src="user.avatar"/>
                        </v-avatar>
                        <v-btn
                            color="primary"
                            @click.stop="dialog = true"
                        >
                            Обновить Аватар
                        </v-btn>
                        <v-dialog
                            v-model="dialog"
                            max-width="600"

                        >
                            <v-card :loading="loading">
                                <v-card-title class="headline">Загрузить фотографию</v-card-title>
                                <div class="text-center">
                                    <input type="file"  accept="image/png, image/jpeg" @change="onFileSelected">
                                </div>
                                <v-card-text class="red--text" v-if="error != null">{{error}}</v-card-text>
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
                    </v-flex>
                    <v-card-text v-if="user.name != ''" label="Имя">{{ user.name }}</v-card-text>
                    <v-card-text v-if="user.surname != ''" label="Фамилия">{{ user.surname }}</v-card-text>
                    <v-card-text v-if="user.patronymic != ''" label="Отчество">{{ user.patronymic }}</v-card-text>
                    <v-card-text v-if="user.group != ''" label="Группа">{{ user.group }}</v-card-text>
                    <v-card-text v-if="user.permission != ''" label="Группа">{{ user.permission == 1 ? "Студент" : user.permission == 2 ? "Учитель" : "Админ" }}</v-card-text>
                </v-card-text>
            </v-card>
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
            loading: false,
        }
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
        }),
    },
    updated() {
        this.checkSelectedFile();
    },
    methods: {
        checkSelectedFile() {
          if(this.selectedFile === null) {
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
                imgdata.append('image', this.selectedFile, this.selectedFile.name);
                await axios.post('/api/updateuserimage', imgdata, {
                    headers: {
                        "Authorization": "Bearer " + localStorage.getItem('token')
                    }
                }).then(responce => {
                        this.error = null;
                        this.dialog = false;
                        this.loading = false;
                        this.user.avatar = responce.data.url;
                    }
                ).catch(() => {
                    this.requestHasBeenSent = false;
                    this.disabled = false;
                    this.loading = false;
                    this.error = "Изображение должно иметь расширение \"jpg, png, jpeg\""
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

<style scoped>

</style>
