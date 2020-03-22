<template>
<div>
    <!-- side contents -->
    <div class="side-div">
        <p>VueMuzikApp</p>
    </div>
    <div class="navs">
        <nav>
            <ul>
                <li class="active">Music</li>
                <li>Upload News</li>
                <li>Another Nav</li>
                <br>
                <li @click="data.methods.logout()">Logout</li>
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
    }
}
</script>

<style src="./../styles/nav-styles.css" scoped>

</style>