<template>
    <v-app class="mx-10">
        <Header />
        <v-container fluid>
            <v-row class="justify-center">
                <v-col class="d-flex" cols="12" sm="6">
                    <v-select
                        class="mr-2"
                        label="Solo field"
                        solo
                        transition="scroll-y-transition"
                    ></v-select>
                    <v-select
                        label="Solo field2"
                        solo
                        transition="scroll-y-transition"
                    ></v-select>
                </v-col>
            </v-row>
            <v-container style="height: 400px;" v-if="!students">
                <v-row
                    class="fill-height"
                    align-content="center"
                    justify="center"
                >
                    <v-col
                        class="subtitle-1 text-center"
                        cols="12"
                    >
                        Пожалуйста подождите, идёт загрузка студентов
                    </v-col>
                    <v-col cols="6">
                        <v-progress-linear
                            color="primary accent-4"
                            indeterminate
                            rounded
                            height="6"
                        ></v-progress-linear>
                    </v-col>
                </v-row>
            </v-container>
            <v-card
                v-for="student in students"
                :key="student">
                <v-icon class="float-right ma-1"
                        :value="hover"
                        color="red"
                        @click="deleteUser(student['id'])"
                        v-if="user['permission_id'] > 3">mdi-delete</v-icon>
                <v-list-item three-line :to="'user_'+student['id']">
                    <v-list-item-avatar tile size="50" color="primary">
                        <v-img :src="student['avatar']"></v-img>
                    </v-list-item-avatar>
                    <v-list-item-content>
                        <span>{{ student['name'] }} {{ student['surname'] }} {{ student['patronymic'] }} {{ student['group'] }}</span>
                    </v-list-item-content>
                </v-list-item>
            </v-card>
        </v-container>
    </v-app>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
    name: "Students",
    data: function() {
        return {
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
    }
};
</script>

<style scoped></style>
