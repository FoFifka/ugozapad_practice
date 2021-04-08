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
import Students from "@/views/Students";
import Users from "@/views/Users";
import AnotherUserProfile from "@/views/AnotherUserProfile";
import WelcomePage from "@/views/WelcomePage";

Vue.use(VueRouter);

const routes = [
    {
        path: "/",
        component: Home,
        name: "Главная"
    },
    {
        path: "/welcomepage",
        name: "Добро пожаловать!",
        component: WelcomePage
    },
    {
        path: "/profile",
        name: "Профиль",
        component: Profile
    },
    {
        path: "/user_:user_id",
        name: "Профиль",
        props: true,
        component: AnotherUserProfile
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
    },
    {
        path: "/students",
        name: "Студенты",
        props: true,
        component: Students
    },
    {
        path: "/users",
        name: "Пользователи",
        props: true,
        component: Users
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
