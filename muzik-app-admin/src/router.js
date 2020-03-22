import Vue from 'vue';
import Router from 'vue-router';
import AdminLogin from './components/AdminLogin';
import NavBar from './components/nav';
import ManageMus from './components/ManageMus';

Vue.use(Router);

export default new Router ({
    routes: [
        {
            path: '/',
            redirect: '/admin-login',
        },

        {
            path: '/admin-login',
            name: 'admin-login',
            component: AdminLogin,
        },

        {
            path: '/n',
            component: NavBar,
            children : [
                {
                    path: '/n/manage-music',
                    name: 'man-mus',
                    component: ManageMus
                }
            ]
        }
    ]
})