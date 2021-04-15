<template>
    <v-app class="mx-10">
        <Header />
        <v-container fluid>
            <v-row v-if="this_user['permission_id'] > 2" class="ma-2">
                <v-spacer/>
                <v-btn class="float-right" @click="dialog_add_user = true">Добавить пользователя</v-btn>
            </v-row>
                <v-data-table
                    :loading="loading_table"
                    loading-text="Loading... Please wait"
                    :headers="headers"
                    :items="users"
                    :items-per-page="15"
                    :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            v-if="this_user['permission_id'] > 2"
                            @click="deleteUser(item['id'])"
                            small
                        >
                            mdi-delete
                        </v-icon>
                        <v-icon
                            class="ml-1"
                            @click="goToUser(item)"
                            small
                        >
                            mdi-account-box
                        </v-icon>
                    </template>
                </v-data-table>
            <v-dialog v-model="dialog_add_user" max-width="1200">
                <v-card :loading="loading" class="px-3">
                    <v-card-title class="headline">
                        Добавить пользователя
                    </v-card-title>
                    <v-text-field
                        v-model="adduser_name_input"
                        clear-icon="mdi-close-circle"
                        clearable
                        label="Имя"
                        type="text">
                    </v-text-field>
                    <v-text-field
                        v-model="adduser_surname_input"
                        clear-icon="mdi-close-circle"
                        clearable
                        label="Фамилия"
                        type="text">
                    </v-text-field>
                    <v-text-field
                        v-model="adduser_patronymic_input"
                        clear-icon="mdi-close-circle"
                        clearable
                        label="Отчество(не обязательно)"
                        type="text">
                    </v-text-field>
                    <v-text-field
                        v-model="adduser_email_input"
                        clear-icon="mdi-close-circle"
                        clearable
                        label="Почта"
                        type="text">
                    </v-text-field>
                    <v-radio-group
                        v-model="selected_radioButton_gender"
                        mandatory>
                        <v-radio
                            v-for="gender in genders"
                            :key="gender"
                            :label="`${gender['gender']}`"
                            :value="gender"
                        ></v-radio>
                    </v-radio-group>
                    <v-radio-group
                        v-model="selected_radioButton"
                        mandatory>
                        <v-radio
                            v-for="permission in permissions"
                            :key="permission"
                            :label="`${permission['permission']}`"
                            :value="permission"
                        ></v-radio>
                    </v-radio-group>
                    <v-list-group v-if="selected_radioButton['id'] == 2"
                        no-action
                        sub-group
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>Компания</v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-radio-group
                            v-model="selected_radioButton_company"
                            mandatory>
                            <v-radio
                                v-for="company in companies"
                                :key="company"
                                :label="`${company['company_name']}`"
                                :value="company"
                            ></v-radio>
                        </v-radio-group>
                    </v-list-group>
                    <v-list-group v-if="selected_radioButton['id'] == 1"
                                  no-action
                                  sub-group
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>Группа</v-list-item-title>
                            </v-list-item-content>
                        </template>
                        <v-radio-group
                            v-model="selected_radioButton_group"
                            mandatory>
                            <v-radio
                                v-for="group in groups"
                                :key="group"
                                :label="`${group['group_name']}`"
                                :value="group"
                            ></v-radio>
                        </v-radio-group>
                    </v-list-group>
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
                            :disabled="disabled"
                            :loading="loading"
                            color="primary"
                            text
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
import store from "@/store";
import router from "@/router";
import users from "@/store/users";

export default {
    name: "Users",
    data: function() {
        return {
            headers: [
                {
                    text: 'Имя',
                    align: 'start',
                    value: 'name'
                },
                {
                    text: 'Фамилия',
                    value: 'surname'
                },
                {
                    text: 'Отчество',
                    value: 'patronymic'
                },
                {
                    text: 'Роль',
                    value: 'permission'
                },
                {
                    text: 'Группа',
                    value: 'group'
                },
                {
                    text: 'Оценка',
                    value: 'mark'
                },
                {
                    text: 'Действия',
                    value: 'actions',
                    sortable: false
                },
            ],
            selected_radioButton: 0,
            selected_radioButton_company: 0,
            selected_radioButton_group: 0,
            selected_radioButton_gender: 0,
            adduser_name_input: "",
            adduser_surname_input: "",
            adduser_patronymic_input: "",
            adduser_email_input: "",
            adduser_group_input: null,
            adduser_companies_input: null,
            dialog_add_user: false,
            loading_table: true,
            loading: false,
            disabled: false,
            hover: false,
            usershasloaded: false


        };
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            this_user: "auth/user",
            users: "users/users",
            permissions: "users/permissions",
            companies: "companies/companies",
            groups: "users/groups",
            genders: "users/genders"
        })
    },
    mounted() {
      if(users && !this.usershasloaded) {
          this.usershasloaded = true
          this.loading_table = false;
      }
    },
    methods: {
        deleteUser(id) {
            axios.delete("/api/deleteuser", { params: { "id": id } }).then(() => {
                //location.reload();
                store.dispatch('users/getusers');
                store.dispatch('auth/signIn');
            });
        },
        addUser() {
            this.disabled = true
            this.loading = true
            if(this.selected_radioButton['id'] == 2) {
                this.adduser_companies_input = this.selected_radioButton_company['id']
            }
            if(this.selected_radioButton['id'] == 1) {
                this.adduser_group_input = this.selected_radioButton_group['id']
            }
            axios.post("/api/createuser", {
                "name": this.adduser_name_input,
                "surname": this.adduser_surname_input,
                "patronymic": this.adduser_patronymic_input,
                "email": this.adduser_email_input,
                "gender_id": this.selected_radioButton_gender['id'],
                "group_id": this.adduser_group_input,
                "permission_id": this.selected_radioButton["id"],
                "companies_id": this.adduser_companies_input
            }).then(response => {
                this.users.push(response.data);
                this.adduser_name_input = "";
                this.adduser_surname_input = "";
                this.adduser_patronymic_input = "";
                this.adduser_email_input = "";
                this.adduser_group_input = null;
                this.adduser_companies_input = null;
                this.dialog_add_user = false;
                this.disabled = false;
                this.loading = false;
            }).catch(() => {
                this.disabled = false;
                this.loading = false;
            });
        },
        goToUser(item) {
            router.replace('user_'+item['id'])
        }
    }
};
</script>

<style scoped>

</style>
