import Vue from 'vue';
import Router from 'vue-router';
import AdminLogin from './components/AdminLogin';
import NavBar from './components/nav';
import ManageMus from './components/ManageMus';
import News from './components/News';

Vue.use(Router);

const route = new Router ({
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
                },

                {
                    path: '/n/news',
                    name: 'news',
                    component: News
                }
            ]
        }
    ]
})

route.beforeEach( (to, from, next) => {
    if ( to.name !== 'admin-login' && !localStorage.getItem('user') ) {
        next({name: 'admin-login'})
    } else {
        next()
    }
} )

export default route