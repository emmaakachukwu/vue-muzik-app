<template>
<div>
    <div class="btn-holder">
        <button class="upl-btn" v-on:click='modal = true'>Upload Muzik</button>
    </div>
    <section class="holder" v-if="files == 0">
        <h2>No Music file available</h2>
    </section>
    <section class="holder" v-if="files">
        <div class="slate" v-for="(file, index) in files" :key="index">
            <img :src="covers[index] || cover" alt="logo">
            <div>
                <b class="file-details">{{file.title}}</b>
                <br>
                <p class="file-details">Artiste: {{file.artiste}}</p>
                <p class="file-details">Feat: {{file.featured || 'Nill'}}</p>
                <div style="text-align: right">
                    <button class="edit" v-on:click="edit(index, file.id)">Edit</button>
                    <button class="del" v-on:click="del(file.title, file.id)">Delete</button>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-holder" v-if='modal'>
        <div class="modal-box">
            <div v-if="!delMode">
                <span class='close' @click='clearFields'>&times;</span>
                <br>
                <form class="modal-form" @submit.prevent="uploadMuzik">
                    <h3>{{!editMode ? 'Upload Muzik' : 'Edit Muzik'}}</h3>
                    <br>
                    <label>Cover Image</label>
                    <br>
                    <div class="cover-container">
                        <img class="cover" :src="url ? url : cover" alt="cover image">
                        <label for="file" class="ch-cov-btn">{{ !editMode ? 'Select cover image' : 'Change cover image'}}</label>
                        <input type="file" name="file" id="file" class="upload" @change="onCoverSelect" accept="image/*">
                    </div>
                    <br>
                    <div v-if='!editMode'>
                        <label>Audio File <span>*</span></label>
                        <input type="file" id="audio" accept="audio/*" @change="onAudioSelect">
                        <br>
                    </div>
                    <label>Title <span>*</span> </label>
                    <br>
                    <input placeholder='Enter muzik title' v-model='ff.title' >
                    <br>
                    <label>Artiste <span>*</span></label>
                    <br>
                    <input placeholder="Enter artiste name" v-model='ff.artiste' >
                    <br>
                    <label>Featured</label>
                    <br>
                    <input placeholder="Enter featured artistes if any. Seperate names with commas" v-model="ff.featured">
                    <br>
                    <label>Notes</label>
                    <br>
                    <textarea rows="3" placeholder="Any notes or info about muzik" v-model="ff.info"></textarea>
                    <br>
                    <p class="error">{{error}}</p>
                    <br>
                    <div style='text-align: right'>
                        <button class="upl-btn">{{!editMode ? 'Upload Muzik' : 'Update Muzik'}}</button>
                    </div>
                </form>
            </div>
            <div v-else>
                <div class="slate">
                    <h3>Delete Muzik</h3>
                    <br>
                    <h2 class="h2-confirm">You are about to delete <i>{{ff.title}}</i></h2>
                    <br>
                    <h3 class="h3-sub-confirm">Do you wish to continue?</h3>
                    <br>
                    <div style="text-align: center">
                        <button class="confirm-del" @click='confirmDel'>Yes, delete</button>
                        <button class="cancel-del" @click="clearFields">No, do not delete</button>
                    </div>
                    <br>
                    <p class="error">{{error}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin';

export default {
    name: 'ManageMus',
    mixins: [DataMixin],
    data () {
        return {
            data: DataMixin,
            cover: require("./../assets/avatar.png"),
            covers: [],
            audio: null,
            url: null,
            files: null,
            modal: false,
            error: null,
            fd: null,
            editMode: false,
            id: Number,
            cover_img: null,
            delMode: false,

            ff: {
                title: '',
                artiste: '',
                featured: '',
                info: ''
            }            
        }
    },

    created () {
        this.onLoad('load')
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
        
        onLoad(from = "form"){
            from != 'form' ? this.progressLoader('show') : ""
            this.data.methods.postMethod( {key: 101} ).then(
                res => {
                    if ( res.data.code == 11 ) {
                        this.files = res.data.msg;
                        this.files.forEach(e => {
                            e.cover_path ? this.covers.push(require("./../assets/covers/" + e.cover_path)) : this.covers.push(null);
                        });
                    } else {
                        this.files = 0
                    }
                    this.progressLoader('h')
                }
            );
        },
    
        onCoverSelect(event){
            this.cover = event.target.files[0];
            this.url = URL.createObjectURL(this.cover);
        },

        onAudioSelect(event){
            this.audio = event.target.files[0];
        },

        edit(i, id){
            this.id = id;
            let details = this.files[i];
            this.cover_img = this.files[i].cover_path;
            Object.keys(this.ff).forEach(i => {
                this.ff[i] = details[i];
            });
            this.url = this.cover_img ? require("./../assets/covers/" + this.cover_img) : null;
            this.editMode = true;
            this.modal = true;
        },

        del(title, id){
            this.ff.title = title;
            this.id = id;
            this.delMode = true;
            this.modal = true;
        },

        confirmDel(){
            this.progressLoader('show')
            let p = {
                key: 203,
                email: localStorage.getItem('user'),
                id: this.id
            }
            this.data.methods.postMethod(p).then(
                res => {
                    if ( res.data.code == 11 ) {
                        this.clearFields()
                        this.onLoad()                        
                    } else {
                        this.error = res.data.msg
                        this.progressLoader('h')
                    }
                }
            );
        },

        clearFields(){
            this.modal = false;
            this.editMode = false;
            this.delMode = false;
            this.error = "";
            this.url = null;
            this.cover = require("./../assets/avatar.png");
            this.audio = null;
            this.cover_img = null;
            let audioInput = document.getElementById('audio');
            audioInput ? audioInput.value = "" : null;
            Object.keys(this.ff).forEach(i => {
                this.ff[i] = "";
            });
        },

        submit(cover_path){
            if ( !this.editMode ) {
                this.fd.set('audio', this.audio, this.ff.artiste + '|' + this.ff.title + '|' + this.audio.name)
                this.data.methods.postMethod(this.fd).then(
                    res => {
                        if ( res.data.code == 11 ) {
                            let p = {
                                key: 202,
                                title: this.ff.title,
                                artiste: this.ff.artiste,
                                featured: this.ff.featured,
                                info: this.ff.info,
                                uploaded_by: localStorage.getItem('user'),
                                cover: cover_path,
                                audio: res.data.msg
                            };
                            this.data.methods.postMethod(p).then(
                                res => {
                                    if ( res.data.code == 11 ) {
                                        this.onLoad();
                                        this.clearFields();
                                    } else {
                                        this.error = res.data.msg;
                                        this.progressLoader('h')
                                    }
                                }
                            );
                        } else {
                            this.error = res.data.msg
                            this.progressLoader('h')
                        }
                    }
                );
            } else {
                let p = {
                    key: 202,
                    title: this.ff.title,
                    artiste: this.ff.artiste,
                    featured: this.ff.featured,
                    info: this.ff.info,
                    cover: this.url.slice(0, 4) == 'blob' ? cover_path : this.cover_img,
                    id: this.id
                };
                this.data.methods.postMethod(p).then(
                    res => {
                        if ( res.data.code == 11 ) {
                            this.onLoad();
                            this.clearFields();
                        } else {
                            this.error = res.data.msg;
                            this.progressLoader('h')
                        }                        
                    }
                );
            }
        },

        uploadMuzik(){
            var error;
            if ( !this.audio && !this.editMode ) {
                this.error = "No audio file has been selected";
                error = this.error;
            } else {
                let required = ['title', 'artiste'];
                Object.keys(this.ff).forEach(i => {
                    if ( required.includes(i) && this.ff[i].split(' ').join('') == "" ) {
                        this.error = i.charAt(0).toUpperCase() + i.slice(1) + ' is required';
                        error = this.error;
                    }
                });
            }            

            if ( error == null ) {
                this.progressLoader('show')
                this.fd = new FormData();
                if ( this.url && this.url.slice(0, 4) == 'blob' ) {
                    this.fd.set('cover', this.cover, this.ff.title+'|'+this.ff.artiste+'|'+this.cover.name);
                    this.data.methods.postMethod(this.fd).then(
                        res => {
                            if ( res.data.code == 11 ) {
                                var cover_path = res.data.msg;
                                this.submit(cover_path);
                            } else {
                                this.error = res.data.msg;
                                this.progressLoader('h')
                            }                           
                        }
                    );
                } else {
                    this.submit(null);
                }
            }
        }
    }
}
</script>

<style src="./../styles/man-mus-styles.css" scoped>

</style>