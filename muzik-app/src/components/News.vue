<template>
<div>
    <section class="holder" v-if="news == 0">
        <h2>No News available</h2>
    </section>
    <section class="holder" v-if="news">
        <div class="slate" v-for="(n, index) in news" :key="index" @click="openNews(n.title)">
            <img :src="covers[index] || cover" alt="cover image">
            <div>
                <br>
                <b class="file-details">{{n.title}}</b>
            </div>
        </div>
    </section>
</div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'

export default {
    name: 'News',

    created () {
        this.onLoad()
    },

    data(){
        return {
            data: DataMixin,
            news: null,
            cover: require("./../assets/avatar.png"),
            covers: [],
        }
    },

    methods: {
        onLoad(){
            // from != 'form' ? this.progressLoader('show') : ""
            this.data.methods.postMethod( {key: 204} ).then(
                res => {
                    if ( res.data.code == 11 ) {
                        this.news = res.data.msg;
                        this.news.forEach(e => {
                            e.cover_path ? this.covers.push(require("./../assets/covers/" + e.cover_path)) : this.covers.push(null);
                        });
                    } else {
                        this.news = 0
                    }
                    // this.progressLoader('h')
                }
            );
        },

        openNews(title){
            let t = title.split(' ').join('-')
            this.$router.push({path: 'read/'+t})
        }
    }
}
</script>

<style>

</style>