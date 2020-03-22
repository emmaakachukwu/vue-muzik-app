import Vue from 'vue';
import Router from 'vue-router';
import Home from './components/Home';
import Download from './components/Download';
import Login from './components/Login';
import Signup from './components/Signup';

Vue.use(Router);

const route = new Router ({
    routes: [
        {
            path: '/',
            redirect: '/home'
        },

        {
            path: '/home',
            name: 'home',
            component: Home
        },

        {
            path: '/download/:name',
            name: 'download',
            component: Download
        },

        {
            path: '/login',
            name: 'login',
            component: Login
        },

        {
            path: '/signup',
            name: 'signup',
            component: Signup
        }
    ]
})

route.beforeEach( (to, from, next) => {
    if ( (to.name === 'login' || to.name === 'signup') && localStorage.getItem('user') ) {
        next({name: 'home'})
    } else {
        next()
    }
})

export default route