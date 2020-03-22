<template>
    <body>
        <!-- Header and Nav -->
       
        <!-- main content -->
        <section class="holder" v-if="!files">
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

        <footer>
            <section class="follow">
                <p> ( : </p>
            </section>
            <section class="year">
                <p>© 2020</p>
            </section>
        </footer>
    </body>
</template>

<script>
    import { DataMixin } from './../mixins/datamixin';
    import router from './../router';

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
            onLoad(){
                this.data.methods.postMethod( {key: 101} ).then(
                    res => {
                        this.files = res.data.msg;
                        this.files.forEach(e => {
                            e.cover_path ? this.covers.push(require("./../assets/covers/" + e.cover_path)) : this.covers.push(null);
                        });
                    }
                );
            },

            openMuzik(artiste, title){
                let a = artiste.split(' ').join('_')
                let t = title.split(' ').join('_')
                let at = a + '-' + t;
                router.push({path: 'download/' + at})
            }
        }
    }

</script>


<style>

</style>