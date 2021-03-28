<template>
    <v-app class="mx-10">
        <Header/>
        <v-container fluid>
            <v-btn class="float-right" @click="dialog_add_user = true">Добавить пользователя</v-btn>
            <v-row class="justify-center">
                <v-col
                    class="d-flex"
                    cols="12"
                    sm="6"
                >
                    <v-select
                        label="Solo field"
                        solo
                        class="mr-2"
                        transition="scroll-y-transition"
                    ></v-select>
                    <v-select
                        label="Solo field2"
                        solo
                        transition="scroll-y-transition"
                    ></v-select>

                </v-col>
            </v-row>
            <v-card
                v-for="student in students"
                :key="student">
                    <v-icon class="float-right ma-1"
                        :value="hover"
                        color="red"
                        @click="deleteUser(student['id'])"
                        v-if="user['permission'] > 3">mdi-delete</v-icon>
                    <v-list-item three-line :to="'user_'+student['id']">
                        <v-list-item-avatar tile size="50" color="primary">
                            <v-img :src="student['avatar']"></v-img>
                        </v-list-item-avatar>
                        <v-list-item-content>
                            <span>{{ student['name'] }} {{ student['surname'] }} {{ student['patronymic'] }} {{ student['group'] }}</span>
                        </v-list-item-content>
                    </v-list-item>

            </v-card>
            <v-dialog v-model="dialog_add_user" max-width="1200">
                <v-card :loading="loading" class="px-3">
                    <v-card-title class="headline">
                        Добавить пользователя
                    </v-card-title>
                    <v-text-field
                        clearable
                        v-model="adduser_login_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Логин">
                    </v-text-field>
                    <v-text-field
                        clearable
                        v-model="adduser_name_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Имя">
                    </v-text-field>
                    <v-text-field
                        clearable
                        v-model="adduser_surname_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Фамилия">
                    </v-text-field>
                    <v-text-field
                        clearable
                        v-model="adduser_patronymic_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Отчество(не обязательно)">
                    </v-text-field>
                    <v-text-field
                        clearable
                        v-model="adduser_email_input"
                        clear-icon="mdi-close-circle"
                        type="text"
                        label="Почта(не обязательно)">
                    </v-text-field>
                    <v-radio-group
                        v-model="selected_radio"
                        mandatory>
                        <v-radio
                            v-for="permission in permissions"
                            :key="permission"
                            :label="`${permission['permission']}`"
                            :value="permission"
                        ></v-radio>
                    </v-radio-group>
                    <v-card-actions>
                        <v-btn
                            color="primary"
                            text
                            @click="dialog_add_user = false"
                        >
                            Отмена
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            text
                            :disabled="disabled"
                            @click="addUser"
                        >
                            Добавить
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
    </v-app>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";
export default {
    name: "Users",
    data: function() {
        return {
            selected_radio: null,
            adduser_login_input: "",
            adduser_name_input: "",
            adduser_surname_input: "",
            adduser_patronymic_input: "",
            adduser_email_input: "",
            adduser_group_input: "",
            adduser_permission_checkbox: false,
            adduser_companies_input: "",
            dialog_add_user: false,
            loading: false,
            disabled: false,
            students: null,
            hover: false,
            permissions: null,
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
    beforeMount() {
        axios.get('/api/getusers').then(response => {
            this.students = response.data;
        });
        axios.get('/api/getpermissions').then(response => {
            this.permissions = response.data;
        })
    },
    methods: {
        deleteUser(id) {
            axios.delete('/api/deleteuser', { params: { 'id': id }}).then(() => {
                location.reload();
            })
        },
        addUser() {
            axios.post('/api/createuser', {
                'login': this.adduser_login_input,
                'name': this.adduser_name_input,
                'surname': this.adduser_surname_input,
                'patronymic': this.adduser_patronymic_input,
                'password': 'password',
                'email': this.adduser_email_input,
                'group_id': '',
                'permission_id': this.selected_radio['id'],
                'companies_id': ''
            }).then(() => {
               location.reload();
            }).catch(error => {
                console.log(error);
            });
        }
    }
};
</script>

<style scoped>

</style>
