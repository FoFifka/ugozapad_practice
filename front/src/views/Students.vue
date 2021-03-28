<template>
    <v-app class="mx-10">
        <Header/>
        <v-container fluid>
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
            <v-card>
                <v-list-item three-line
                    v-for="student in students"
                    :key="student">
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
            students: null,
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
        axios.get('/api/getstudents').then(response => {
            this.students = response.data;
        });
    }
};
</script>

<style scoped>

</style>
