<template>
    <body>       
        <section class="holder" v-if="files == 0">
            No file available
        </section>
        <section class="holder" v-else>
            <div class="slate" v-for="(file, i) in files" :key="file.id" v-on:click="openMuzik(file.artiste, file.title)">
                <img :src="covers[i] || cover" alt="logo">
                <div>
                    <b class="file-details">{{file.title}}</b>
                    <br>
                    <p class="file-details">Artiste: {{file.artiste}}</p>
                    <p class="file-details">Feat: {{file.featured || 'Nill'}}</p>
                </div>
            </div>
        </section>
    </body>
</template>

<script>
    import { DataMixin } from './../mixins/datamixin';

    export default {
        name: 'Muzik',
        data () {
            return {
                data: DataMixin,
                files: null,
                cover: require("./../assets/avatar.png"),
                covers: []
            }
        },

        created () {
            this.onLoad();
        },

        methods: {
            progressLoader(req){
                if ( req == 'show' ) {
                    this.loader = this.$loading.show({
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: true,
                        transition: 'fade',
                        color: '#1d5bce',
                        loader: 'spinner',
                    });
                } else {
                    this.loader.hide()
                }
            },

            onLoad(){
                this.progressLoader('show')
                this.data.methods.postMethod( {key: 101} ).then(
                    res => {
                        if ( res.data.code == 11 ) {
                            this.files = res.data.msg;
                            this.files.forEach(e => {
                                e.cover_path ? this.covers.push(require("./../assets/covers/" + e.cover_path)) : this.covers.push(null);
                            });
                            this.progressLoader('h')
                        } else {
                            this.files = 0
                            this.progressLoader('h')
                        }
                    }
                );
            },

            openMuzik(artiste, title){
                let a = artiste.split(' ').join('_')
                let t = title.split(' ').join('_')
                let at = a + '-' + t;
                this.$router.push({path: 'download/' + at})
            }
        }
    }

</script>


<style>

</style>