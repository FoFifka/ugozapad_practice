import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home";
import About from "../views/About";
import Login from "../views/Login";
import Profile from "../views/Profile";
import Groups from "../views/Groups";
import Companies from "../views/Companies";
import Company from "@/views/Company";
import Vacancy from "@/views/Vacancy";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: Home,
        name: "Главная"
    },
    {
        path: "/profile",
        component: Profile,
        name: "Профиль"
    },
    {
        path: "/about",
        component: About,
        name: "О нас"
    },
    {
        path: "/signin",
        component: Login,
        name: "Авторизация"
    },
    {
        path: "/groups",
        component: Groups,
        name: "Группы"
    },
    {
        path: "/companies",
        component: Companies,
        name: "Компании"
    },
    {
        path: "/company_:company_id",
        name: "Компания",
        props: true,
        component: Company
    },
    {
        path: "/vacancy_:vacancy_id",
        name: "Вакансия",
        props: true,
        component: Vacancy
    }
];

const router = new VueRouter({
    mode: "history",
    base: process.env.BASE_URL,
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.name;
    next();
});
export default router;
