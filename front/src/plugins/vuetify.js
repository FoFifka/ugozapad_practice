import Vue from "vue";
import Vuetify from "vuetify/lib/framework";
import ru from "vuetify/lib/locale/ru";

Vue.use(Vuetify);

export default new Vuetify({
    lang: {
        locales: { ru },
        current: "ru"
    },
    theme: {
        themes: {
            light: {
                primary: '#123f91',
                secondary: '#64B5F6',
            }
        }
    }
});
