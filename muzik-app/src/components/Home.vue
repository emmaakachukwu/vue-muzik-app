<template>
    <body>
        <!-- Header and Nav -->
       
        <!-- main content -->
        <section class="holder" v-if="files == 0">
            No file available
        </section>
        <section class="holder" v-else>
            <div class="slate" v-for="(file, i) in files" :key="i" v-on:click="openFile(file.artiste, file.title, file.type)">
                <img :src="covers[i] || cover" alt="cover">
                <div>
                    <br>
                    <p class="file-details" v-if="file.type == 'download'">{{file.title}} by {{file.artiste}} <span v-if="file.featured">(feat. {{file.featured}})</span></p>
                    <p class="file-details" v-else>{{file.title}}</p>
                </div>
            </div>
        </section>
    </body>
</template>

<script>
    import { DataMixin } from './../mixins/datamixin';

    export default {
        name: 'Home',
        // mixins: [DataMixin],
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
                this.data.methods.postMethod( {key: 106} ).then(
                    res => {
                        if ( res.data.code == 11 ) {
                            console.log(res.data.msg)
                            this.files = res.data.msg;
                            this.files.sort(function(a, b){
                                let timeA = Date.parse(a.time)
                                let timeB = Date.parse(b.time)
                                let result;
                                if ( timeA > timeB ) {
                                    result = 1
                                } else if ( timeA < timeB ) {
                                    result = -1
                                }
                                return result
                            })
                            this.files.reverse()
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

            openFile(artiste, title, type){
                if ( type == 'download' ) {
                    let a = artiste.split(' ').join('_')
                    let t = title.split(' ').join('_')
                    let at = a + '-' + t;
                    this.$router.push({path: 'download/' + at})
                } else {
                    let t = title.split(' ').join('-')
                    this.$router.push({path: 'read/'+t})
                }
            }
        }
    }

</script>


<style>

</style>