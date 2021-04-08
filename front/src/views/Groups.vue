<template>
    <app>
        <Header/>

        <v-container style="height: 400px;" v-if="!groups">
            <v-row
                class="fill-height"
                align-content="center"
                justify="center"
            >
                <v-col
                    class="subtitle-1 text-center"
                    cols="12"
                >
                    Пожалуйста подождите, идёт загрузка групп
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
            v-for="group in groups "
            :key="group">
            <v-card-title>{{ group['group_name']}}</v-card-title>
        </v-card>
    </app>
</template>

<script>
import Header from "@/components/Header";
import {mapGetters } from "vuex";

export default {
    components: { Header },
    data: () => ({
        checked: false,
        permission_id: null
    }),
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
            groups: 'users/groups'
        }),
    },
};

</script>
