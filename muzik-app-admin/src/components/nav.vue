<template>
<div>
    <!-- side contents -->
    <div class="side-div">
        <p>VueMuzikApp</p>
    </div>
    <div class="navs">
        <nav>
            <ul>
                <li><router-link :to="{name: 'man-mus'}" active-class="active">Music</router-link></li>
                <li><router-link :to="{name: 'news'}" active-class="active">News</router-link></li>
                <li><router-link to="#" active-class="active">Another Nav</router-link></li>
                <br>
                <li id="logout" @click="logout">Logout</li>
            </ul>
        </nav>
    </div>

    <!-- main contents -->
    <div class="main-content">
        <router-view/>
    </div>
</div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin';

export default {
    name: 'NavBar',

    data(){
        return {
            data: DataMixin
        }
    },

    methods: {
        logout(){
            localStorage.clear();
            this.$router.push({name: 'admin-login'});
        }
    }
}
</script>

<style src="./../styles/nav-styles.css" scoped>

</style>