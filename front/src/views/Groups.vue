<template>
    <app>
        <Header/>
        <v-row class="ma-2" v-if="user['permission_id'] > 2">
            <v-spacer/>
            <v-btn @click="dialog_add_group = true">Добавить группу</v-btn>
        </v-row>
        <v-data-table
            :headers="headers"
            :items="groups"
            :items-per-page="15"
            :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-arrow-left',
                    nextIcon: 'mdi-arrow-right'
                }">
            <template v-slot:item.actions="{ item }">
                <!--
                <v-icon
                    small
                    class="mr-2"
                >
                    mdi-pencil
                </v-icon>
                -->
                <v-icon
                    v-if="user['permission_id'] > 2"
                    small
                    @click="deleteGroup(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
        <v-dialog v-model="dialog_add_group" max-width="1200">
            <v-card :loading="loading" class="px-3">
                <v-card-title class="headline">
                    Добавить группу
                </v-card-title>
                <v-text-field
                    v-model="addgroup_input_group_name"
                    clear-icon="mdi-close-circle"
                    clearable
                    label="Название группы"
                    type="text">
                </v-text-field>
                <v-card-actions>
                    <v-btn
                        color="primary"
                        text
                        @click="dialog_add_group = false"
                    >
                        Отмена
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        :disabled="disabled"
                        :loading="loading"
                        color="primary"
                        text
                        @click="addGroup"
                    >
                        Добавить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </app>
</template>

<script>
import Header from "@/components/Header";
import {mapGetters } from "vuex";
import axios from "axios";
import store from "@/store";

export default {
    components: { Header },
    data: () => ({
        headers: [
            {
                text: 'Группа',
                align: 'start',
                value: 'group_name'
            },
            {   text: 'Действия',
                align: 'end',
                value: 'actions',
                sortable: false },
        ],
        dialog_add_group: false,
        dialog_change_group: false,
        addgroup_input_group_name: "",
        loading: false,
        disabled: false,
        checked: false,
        permission_id: null
    }),
    updated() {
        this.disabled = this.addgroup_input_group_name.length < 4;
    },
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
            groups: 'users/groups'
        }),
    },
    methods: {
        addGroup() {
            axios.post('/api/addgroup', {
               'group_name': this.addgroup_input_group_name
            }).then(response => {
                this.groups.push(response.data);

                this.dialog_add_group = false;
                this.addgroup_input_group_name = "";
            });
        },
        deleteGroup(item) {
            axios.delete('/api/deletegroup', { params: {
                    'group_id': item['id']
                }}).then(() => {
                store.dispatch('users/getGroups');
                store.dispatch('users/getusers');
            });
        }
    }
};

</script>
