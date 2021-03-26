<template>
    <v-container fluid>
        <Header />
        <h1 class="text-center my-2">Компании с которыми у нас контракт</h1>
        <v-flex class="wrap row mx-auto justify-center" align-self-center>
            <v-card
                width="350"
                outlined
                class="ma-1"
                v-for="company in companies"
                :key="company.title"
                :to="'/company_'+company['id']"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <v-list-item-title class="headline mb-1">
                            {{ company['company_name'] }}
                        </v-list-item-title>
                        <v-list-item-subtitle>{{
                                company['company_description']
                        }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar tile size="80" color="primary">
                        <v-img :src="company['company_image']"></v-img>
                    </v-list-item-avatar>
                </v-list-item>
            </v-card>
        </v-flex>
    </v-container>
</template>

<script>
import Header from "@/components/Header";
import { mapGetters } from "vuex";
import axios from "axios";

export default {
    name: "Companies",
    data: () => ({
        companies: null
    }),
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
        axios
            .get("/api/getcompanies")
            .then(response => {
                this.companies = response.data;
            })
            .catch(error => {
                console.log(error);
            });
    },
};
</script>

<style scoped></style>
