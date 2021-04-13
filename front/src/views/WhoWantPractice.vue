<template>
    <v-app>
        <Header/>
        <h1 class="text-center">Желающие пройти практику</h1>
        <v-data-table
            :headers="headers"
            :items="willingPracticeUsers"
            :items-per-page="15"
            @click:row="goToUser"
            :footer-props="{
                    showFirstLastPage: true,
                    firstIcon: 'mdi-arrow-collapse-left',
                    lastIcon: 'mdi-arrow-collapse-right',
                    prevIcon: 'mdi-minus',
                    nextIcon: 'mdi-plus'
                }">

        </v-data-table>
    </v-app>
</template>

<script>
import { mapGetters } from "vuex";
import Header from "@/components/Header";
import router from "@/router";

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
              }
          ],
      }
    },
    components: { Header },
    computed: {
        ...mapGetters({
            authenticated: "auth/authenticated",
            user: "auth/user",
            willingPracticeUsers: "auth/willingPracticeUsers"
        }),
    },
    methods: {
        goToUser(row) {
            router.replace('user_'+row['id'])
        }
    }
};
</script>

<style scoped>

</style>
