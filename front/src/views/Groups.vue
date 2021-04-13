<template>
    <app>
        <Header/>
        <v-data-table
            :headers="headers"
            :items="groups"
            :items-per-page="15"
            :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">
            <template v-slot:item.actions>
                <v-icon
                    small
                    class="mr-2"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
    </app>
</template>

<script>
import Header from "@/components/Header";
import {mapGetters } from "vuex";

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
                value: 'actions',
                sortable: false },
        ],
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
