//Layouts
import AppLayout from '../vue/layouts/AppLayout';
import AuthLayout from '../vue/layouts/AuthLayout.vue';
//import GenericLayout from '../vue/layouts/GenericLayout.vue';
//Views
    //Login
import Login from '../vue/views/Login.vue';
import LoginReset from '../vue/views/LoginReset.vue'
    //App
import Dashboard from '../vue/views/Dashboard.vue';
import Profile from '../vue/views/Profile.vue';
import Notification from '../vue/views/Notification.vue';
import About from '../vue/views/About.vue';

import Log from '../vue/views/Log.vue';
//import Evaluation from '../vue/views/Evaluation.vue';
//import PresBook from '../vue/views/PresBook.vue';
//import TextBook from '../vue/views/TextBook.vue';
//import ProgressBook from '../vue/views/ProgressBook.vue';

/*import Classe from '../vue/views/Classe.vue';
import ECUE from '../vue/views/ECUE.vue';
import UE from '../vue/views/UE.vue';
import Syllabus from '../vue/views/Syllabus.vue';
import Progression from '../vue/views/Progression.vue';
*/

export const routes = [
    { path: '/', redirect: '/app' },
    {
        path: '/login',
        component: AuthLayout,
        meta: { requiresGuess: true },
        children: [
            {
                path: '',
                name: 'Login',
                component: Login,
                meta: { hidden: true }
            },
            {
                path: 'reset',
                name: 'LoginReset',
                component: LoginReset,
                meta: { hidden: true }
            }
        ]
    },
    {
        path: '/app',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            //Static pages
            {
                path: '',
                name: 'Dashboard',
                component: Dashboard,
                meta: {
                    key: 1,
                    roles: ['*'],
                    hidden: false,
                    static: false,
                    title: 'Tableau de bord',
                    icon: 'tachometer-alt',
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: true }
                    ]
                }
            },
            /*{
                path: '/app/evals',
                name: 'genericEval',
                component: Evaluation,
                meta: {
                    key: 2,
                    roles: ['student', 'delegate', 'professor', 'director'],
                    hidden: false,
                    static: false,
                    title: 'Evaluations',
                    icon: 'info-circle',
                    exact: false,
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Evaluations', href: '/app/evals', active: true }
                    ]
                },
            },
            {
                path: '/app/pres',
                name: 'genericPresBook',
                component: PresBook,
                meta: {
                    key: 2,
                    roles: ['student', 'delegate', 'professor', 'director', 'inspector'],
                    hidden: false,
                    static: false,
                    title: 'Cahier de présence',
                    icon: 'info-circle',
                    exact: false,
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Cahier de présence', href: '/app/pres', active: true }
                    ]
                },
            },
            {
                path: '/app/text',
                name: 'genericTextBook',
                component: TextBook,
                meta: {
                    key: 2,
                    roles: ['student', 'delegate', 'professor', 'director', 'inspector'],
                    hidden: false,
                    static: false,
                    title: 'Cahier de textes',
                    icon: 'info-circle',
                    exact: false,
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Cahier de textes', href: '/app/text', active: true }
                    ]
                },
            },
            {
                path: '/app/progress',
                name: 'genericProgress',
                component: ProgressBook,
                meta: {
                    key: 2,
                    roles: ['student', 'delegate', 'professor', 'director', 'inspector'],
                    hidden: false,
                    static: false,
                    title: 'Progression des cours',
                    icon: 'info-circle',
                    exact: false,
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Progression des cours', href: '/app/progress', active: true }
                    ]
                },
            },*/
            {
                path: '/app/logs',
                name: 'Log',
                component: Log,
                meta: {
                    key: 3,
                    roles: ['admin'],
                    hidden: false,
                    static: false,
                    title: 'Logs',
                    icon: 'info-circle',
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Logs', href: '/app/logs', active: true }
                    ]
                }
            },
            {
                path: '/app/profile',
                name: "Profile",
                component: Profile,
                meta: {
                    key: 4,
                    roles: ['*'],
                    hidden: false,
                    static: true,
                    title: 'Profile',
                    icon: 'id-card',
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Profile', href: '/app/profile', active: true }
                    ]
                }
            },
            {
                path: '/app/notifs',
                name: 'Notification',
                component: Notification,
                meta: {
                    key: 5,
                    roles: ['*'],
                    hidden: false,
                    static: true,
                    title: 'Notifications',
                    icon: 'bell',
                    breadcrumb: [
                        { text: 'Tableau de bord', href: '/app', active: false },
                        { text: 'Notifications', href: '/app/notifs', active: true }
                    ]
                }
            },
            {
                path: '/app/about',
                name: 'About',
                component: About,
                meta: {
                    key: 6,
                    roles: ['*'],
                    hidden: false,
                    static: true,
                    title: 'A propos',
                    icon: 'info-circle'
                }
            },
        ]
    },
    { path: '/:foo', redirect: '/app' },
    { path: '**', redirect: '/app' }
];
