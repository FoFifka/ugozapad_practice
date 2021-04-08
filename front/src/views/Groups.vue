<template>
    <div>
        <Header/>
        <h1 class="text-center">{{ permission_id = user['permission_id'] }}</h1>
    </div>
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
        }),
    },
    updated() {
            if(this.permission_id < 3) { // проверка сделана через одно место ну и ладно
                location.replace('/');
            }
    }
};

</script>
