<template>
    <div id="app">
        <v-app id="inspire">
            <v-app id="inspire">
                <v-main>
                    <v-container
                        class="fill-height"
                        fluid
                    >
                        <v-row
                            align="center"
                            justify="center"
                        >
                            <v-col
                                cols="12"
                                sm="8"
                                md="4"
                            >
                                <v-card class="elevation-12">
                                    <v-toolbar
                                        color="primary"
                                        dark
                                        flat
                                    >
                                        <v-toolbar-title class="justify-center">Авторизация</v-toolbar-title>
                                        <v-spacer></v-spacer>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-form>
                                            <v-text-field
                                                label="E-mail"
                                                name="email"
                                                v-model="email_input"
                                                prepend-icon="mdi-account"
                                                type="text"
                                            ></v-text-field>

                                            <v-text-field @keyup.enter="submit"
                                                id="password"
                                                label="Пароль"
                                                v-model="password_input"
                                                name="password"
                                                prepend-icon="mdi-lock"
                                                type="password"
                                            ></v-text-field>
                                        </v-form>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" @click="submit" :disabled="disabled" :loading="loading">Войти</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-main>
            </v-app>
        </v-app>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    name: "Login",
    data: () => ({
        email_input: "",
        password_input: "",
        requestHasBeenSent: false, // переменная для предотвращения многоразовой отправки запроса
        token: [],
        loading: false, // параметр состояния анимации загрузки на кнопке
        disabled : false, // параметр состаяния отключения возможности нажатия на кнопку
        userdata: {
            login: "",
            password: ""
        },
        data_has_been_sent: false
    }),
    mounted() {
        if(this.email_input == null || this.password_input.length < 5) {
            this.disabled = true;
        } else {
            this.disabled = false;
        }
    },
    updated() {
        if(!this.data_has_been_sent) {
            if(this.email_input == null || this.password_input.length < 5) {
                this.disabled = true;
            } else {
                this.disabled = false;
            }
        }
    },
    methods: {
        ...mapActions({
            signIn: "auth/signIn"
        }),
        async submit() {
            if(!this.requestHasBeenSent && this.password_input.length >= 5) {
                this.requestHasBeenSent = true;

                this.userdata['email'] = this.email_input;
                this.userdata['password'] = this.password_input;

                this.data_has_been_sent = true;
                this.loading = true; //  вкл анимацию загрузки на кнопке
                this.disabled = true; // отключить возможность клика по кнопке

                this.signIn(this.userdata)
                    .then(() => {
                        this.loading = false;
                        location.replace('/');
                    })
                    .catch(() => {
                        this.loading = false;
                        this.disabled = false;
                        this.data_has_been_sent = false;
                        this.requestHasBeenSent = false;
                    });
            }
        },
    }
};
</script>
