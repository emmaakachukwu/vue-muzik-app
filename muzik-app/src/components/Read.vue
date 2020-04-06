<template>
<div>
    <div v-if="datas">
        <h1 class="news-header">{{datas.title}}</h1>
        <section class="cover-section">
            <img :src="cover" class="cover">
        </section>
        <br>
        <section class="info-holder" v-html="datas.info">
        </section>
        <p class="posted-by">Posted by {{datas.uploaded_by}} on {{datas.time | formatDate}}</p>
    </div>

    <div v-if="datas == 0">
        <h2>What you are looking for is not here</h2>
    </div>

</div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'

export default {
    name: 'Read',

    created(){
        let p = {
            key: 105,
            title: this.title
        }
        this.data.methods.postMethod(p).then(
            res => {
                if ( res.data.code == 11 ) {
                    this.datas = res.data.msg
                    this.cover = this.datas.cover_path ? require('./../assets/covers/' + this.datas.cover_path) : require('./../assets/avatar.png')
                } else {
                    this.datas = 0
                }
            }
        )
    },

    data(){
        return {
            data: DataMixin,
            title: this.$route.params.news.split('-').join(' '),
            datas: null,
            cover: null
        }
    }
}
</script>