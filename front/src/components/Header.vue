<template>
    <span>
        <v-navigation-drawer
            v-model="drawer"
            app

            temporary
            right
            class="d-flex"
        >
            <v-list dense>
                <v-list-item-group
                    v-model="selectedItem"
                    color="primary"
                >
                    <v-list-item
                        v-for="item in items"
                        :key="item"
                        :to="item.to"
                    >
                        <v-list-item-icon>
                            <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/profile">
                        <v-list-item-icon>
                            <v-icon>mdi-account-box</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Профиль</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/groups" v-if="user['permission_id'] > 2">
                        <v-list-item-icon>
                            <v-icon>mdi-format-line-spacing</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Группы</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/companies">
                        <v-list-item-icon>
                            <v-icon>mdi-view-list</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Компании</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item :to="'/company_'+user['companies_id']" v-if="user['permission_id'] == 2">
                        <v-list-item-icon>
                            <v-icon>mdi-clipboard-outline</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Компания</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/students" v-if="user['permission_id'] == 2 || user['permission_id'] > 2">
                        <v-list-item-icon>
                            <v-icon>mdi-account-multiple</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Студенты</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                    <v-list-item to="/users" v-if="user['permission_id'] > 2">
                        <v-list-item-icon>
                            <v-icon>mdi-account-multiple</v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                            <v-list-item-title>Пользователи</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>

            <template v-slot:append>
                <v-list-item class="grey lighten-2 rounded-t-sm" @click.prevent="logOut">
                    <v-list-item-icon>
                        <v-icon>mdi-exit-to-app</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                        <v-list-item-title>Выйти</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>

        </v-navigation-drawer>

        <v-app-bar
            app
            color="primary"
            dark
        >


            <v-toolbar-title align="center">
                    <router-link to="/" class="flex">
                        <v-img src="../assets/uz_logo_white.svg" max-width="50" max-height="50" class="d-inline-block"></v-img>
                    </router-link>
            </v-toolbar-title>
            <v-spacer/>
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        </v-app-bar>
    </span>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    name: "Header",

    props: {
        source: String,
    },
    data: () => ({
        drawer: false,

        selectedItem: 0,
        items: [
            { title: 'Главная', icon: 'mdi-home', to: '/'},
            { title: 'О нас', icon: 'mdi-account-multiple', to: '/about' },
        ],
    }),
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
        })
    },
    methods: {
        ...mapActions({
           logOutAction: 'auth/logOut'
        }),

        logOut() {
            this.logOutAction().then(() => {
                location.replace('/signin');
            })
        }
    },
    updated() {
    }
};
</script>
