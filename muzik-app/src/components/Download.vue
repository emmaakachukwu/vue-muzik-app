<template>
    <div>
        <div v-if="datas">
            <section class="cover-section">
                <img :src="cover" class="cover">
            </section>
            <br>
            <b class="dwnld-head">Download <i>{{datas.title}}</i> by <i>{{datas.artiste}}</i></b>
            <br>
            <section class="info-holder">
                <p>{{datas.info || 'No song info available'}}</p>
            </section>
            <button class="dwnld-btn" @click="downloadFile">DOWNLOAD NOW</button>
        </div>

        <div v-else>
            <h2>What you are looking for is not here</h2>
        </div>
    </div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'
const axios = require('axios').default;

export default {
    name: 'Download',
    created(){
        let p = {
            key: 102,
            title: this.title,
            artiste: this.artiste
        }
        this.data.methods.postMethod(p).then(
            res => {
                if ( res.data.code == 11 ) {
                    this.datas = res.data.msg;
                    this.cover = this.datas.cover_path ? require('./../assets/covers/' + this.datas.cover_path) : require('./../assets/avatar.png')
                    this.audio = require('./../assets/audios/' + this.datas.audio_path)
                } else {
                    this.datas = null
                }                
            }
        )
    },
    
    data(){
        return {
            data: DataMixin,
            title: this.$route.params.name.split('-')[1].split('_').join(' '),
            artiste: this.$route.params.name.split('-')[0].split('_').join(' '),
            datas: null,
            cover: null,
            audio: null
        }
    },

    methods: {
        downloadFile(){
           axios({
                url: this.audio,
                method: 'GET',
                responseType: 'blob'
           }).then(
               res => {
                   let fileURL = window.URL.createObjectURL(new Blob([res.data]));
                   let fileLink = document.createElement('a')
                   fileLink.href = fileURL
                   fileLink.setAttribute('download', this.datas.audio_path)
                   document.body.appendChild(fileLink)
                   fileLink.click()
               }
           )
        }
    }
}
</script>