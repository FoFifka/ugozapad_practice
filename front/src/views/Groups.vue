<template>
    <div class="groups">
        <Header/>
        <h1 class="text-center">{{ permission = user.permission }}</h1>
    </div>
</template>

<script>
import Header from "@/components/Header";
import {mapGetters } from "vuex";

export default {
    components: { Header },
    data: () => ({
        checked: false,
        permission: null
    }),
    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user',
        }),
    },
    updated() {
            if(this.permission != 3) { // проверка сделана через одно место ну и ладно
                location.replace('/');
            }
    }
};

</script>
