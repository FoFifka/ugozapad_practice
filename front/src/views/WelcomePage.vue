<template class="">
    <app>
        <v-card class="flex row align-content-center justify-center mx-10" height="100vh" elevation="0">
            <v-hover class="mx-1"
                v-slot:default="{ hover }">
                <router-link to="/signin">
                    <v-img class="rounded-lg" :src="hover ? '/default_img/rabotadatel2.jpg' : '/default_img/rabotadatel.jpg'" width="50vh" min-height="50vh" max-height="100vh"></v-img>
                </router-link>
            </v-hover>
            <v-hover class="mx-1"
                v-slot:default="{ hover }">
                <router-link to="/signin">
                    <v-img class="rounded-lg" :src="hover ? '/default_img/student2.jpg' : '/default_img/student.jpg'" width="50vh" min-height="50vh" max-height="100vh"></v-img>
                </router-link>
            </v-hover>
        </v-card>
    </app>
</template>

<script>
export default {
    name: "WelcomePage"


};
</script>

<style scoped>

</style>
