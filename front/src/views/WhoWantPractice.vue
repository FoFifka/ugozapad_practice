<template>
    <v-app>
        <Header/>
        <h1 class="text-center">Желающие пройти практику</h1>
        <v-data-table
            :headers="headers"
            :items="willingPracticeUsers"
            :items-per-page="15"
            :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-arrow-left',
                    nextIcon: 'mdi-arrow-right'
                }">
            <template v-slot:item.profile="{ item }">
                <v-icon
                    @click="goToUser(item)"
                >
                    mdi-account-box
                </v-icon>
            </template>
            <template v-slot:item.vacancy="{ item }">
                <v-icon
                    @click="goToVacancy(item)"
                >
                    mdi-clipboard-outline
                </v-icon>
            </template>
            <template v-slot:item.actions="{ item }">
                <!--
                <v-icon
                    @click="AcceptUser(item)"
                >
                    mdi-check
                </v-icon>
                <v-icon
                    class="ml-1"
                    @click="DenyUser(item)"
                >
                    mdi-mdi-window-close
                </v-icon>
                -->
                <v-icon
                    @click="AcceptUser(item)"
                    small
                >
                    mdi-check
                </v-icon>
                <v-icon
                    class="ml-1"
                    @click="DenyUser(item)"
                    small
                >
                    mdi-close
                </v-icon>
            </template>

        </v-data-table>
    </v-app>
</template>

<script>
import { mapGetters } from "vuex";
import Header from "@/components/Header";
import router from "@/router";
import store from "@/store";
import axios from "axios";

export default {
    name: "WhoWantPractice",
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
              },
              {
                  text: 'Профиль',
                  value: 'profile',
                  sortable: false
              },
              {
                  text: 'Вакансия',
                  value: 'vacancy',
                  sortable: false
              },
              {
                  text: 'Дейстивия',
                  value: 'actions',
                  sortable: false
              },
          ],
      }
    },
    components: { Header },
    beforeMount() {
        store.dispatch('auth/getWillingPracticeUsers');
    },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user",
            willingPracticeUsers: "auth/willingPracticeUsers"
        }),
    },
    methods: {
        goToUser(item) {
            router.replace('user_'+item['id'])
        },
        goToVacancy(item) {
            router.replace('vacancy_'+item['vacancy_id'])
        },
        async AcceptUser(item) {
            console.log(item);
            // TODO
        },
        async DenyUser(item) {
            await axios.delete('/api/deletewillingpractice', { params: {
                    'user_id': item['id']
                }}).then(() => {
                store.dispatch('auth/getWillingPracticeUsers');
            });
        }

    }
};
</script>

<style scoped>

</style>
