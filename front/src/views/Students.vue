<template>
    <v-app class="mx-10">
        <Header />
        <v-container fluid>
            <v-data-table
                :headers="headers"
                :items="students"
                :items-per-page="15"
                @click:row="goToUser"
                :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">

            </v-data-table>
        </v-container>
    </v-app>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";
import router from "@/router";

export default {
    name: "Students",
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
                    text: 'Группа',
                    value: 'group'
                },
                {
                    text: 'Оценка',
                    value: 'mark'
                }
            ],
            hover: false,
        };
    },
    components: {
        Header
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user",
            students: "users/students"
        })
    },
    methods: {
        deleteUser(id) {
            axios.delete('/api/deleteuser', { params: { 'id': id }}).then(() => {
                location.reload();
            })
        },
        goToUser(row) {
            router.replace('user_'+row['id'])
        }
    }
};
</script>

<style scoped></style>
