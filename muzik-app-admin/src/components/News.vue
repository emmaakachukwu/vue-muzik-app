<template>
<div>
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button> -->

    <div class="btn-holder">
        <button class="upl-btn" @click="modal = true">Upload News</button>
    </div>
    <section class="holder" v-if="news == 0">
        <h2>No News available</h2>
    </section>
    <section class="holder" v-if="news">
        <div class="slate" v-for="(n, index) in news" :key="index">
            <img :src="covers[index] || cover" alt="cover image">
            <div>
                <b class="file-details">{{n.title}}</b>
                <div style="text-align: right">
                    <button class="edit" v-on:click="edit(index, n.id)">Edit</button>
                    <button class="del" v-on:click="del(n.title, n.id)">Delete</button>
                </div>
            </div>
        </div>
    </section>

    <!-- <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
            </div>
        </div>
    </div> -->
    <div class="modal-holder" v-if='modal'>
        <div class="modal-box">
            <div v-if="!delMode">
                <span class='close' @click='clearFields'>&times;</span>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
                <br>
                <form class="modal-form" @submit.prevent="uploadNews">
                    <h3>{{!editMode ? 'Upload News' : 'Edit News'}}</h3>
                    <br>
                    <label>Cover Image</label>
                    <br>
                    <div class="cover-container">
                        <img class="cover" :src="url ? url : cover" alt="cover image">
                        <label for="file" class="ch-cov-btn">{{ !editMode ? 'Select cover image' : 'Change cover image'}}</label>
                        <input type="file" name="file" id="file" class="upload" @change="onCoverSelect" accept="image/*">
                    </div>
                    <br>
                    <label>Title <span>*</span> </label>
                    <input placeholder='Enter news title' class="form-control" v-model='ff.title'>
                    <br>
                    <label>Info <span>*</span> </label>
                    <!-- <textarea rows="3" placeholder="Enter news info" class="form-control" v-model="ff.info"></textarea> -->
                    <!-- <ckeditor value="hello world"></ckeditor> -->
                    <!-- <br> -->
                    <editor
                        api-key="9r2waqy98spucr1ph5pfm5o9fktwy8klc4s49rgz3s3gabnu"
                        v-model='ff.info'
                        :init="{
                            images_upload_url: 'http://localhost/vue-muzik-app/api/editor-api.php',
                            automatic_uploads: false,
                            height: 400,
                            menubar: false,
                            plugins: [
                            'advlist autolink lists link image imagetools charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount'
                            ],
                            toolbar:
                            'undo redo | formatselect | bold italic backcolor | \
                            alignleft aligncenter aliimagetoolsgnright alignjustify | \
                            image | bullist numlist outdent indent | removeformat | help'
                        }"
                    />
                    <br>
                    <p class="error">{{error}}</p>
                    <br>
                    <div style='text-align: right'>
                        <button class="upl-btn">{{!editMode ? 'Upload News' : 'Update News'}}</button>
                    </div>
                </form>
            </div>
            <div v-else>
                <div class="slate">
                    <h3>Delete News</h3>
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
import { DataMixin } from './../mixins/datamixin'
import Editor from '@tinymce/tinymce-vue'
import * as $ from 'jquery'

export default {
    name: 'News',

    components: {
     'editor': Editor
    },

    mounted () {
        this.onLoad()
       $(document).on('focusin', function(e) {
  if ($(e.target).closest(".tox-tinymce-aux, .moxman-window, .tam-assetmanager-root").length) {
    e.stopImmediatePropagation();
  }
});
    },

    data(){
        return {
            data: DataMixin,
            news: null,
            cover: require("./../assets/avatar.png"),
            covers: [],
            modal: false,
            error: null,
            fd: null,
            editMode: false,
            delMode: false,
            url: null,
            cover_img: null,
            id: Number,

            ff: {
                title: '',
                info: ''
            }
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
                            e.cover ? this.covers.push(require("./../assets/covers/" + e.cover)) : this.covers.push(null);
                        });
                    } else {
                        this.news = 0
                    }
                    // this.progressLoader('h')
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
            this.cover_img = null;
            Object.keys(this.ff).forEach(i => {
                this.ff[i] = "";
            });
        },

        onCoverSelect(event){
            this.cover = event.target.files[0];
            this.url = URL.createObjectURL(this.cover);
        },

        edit(i, id){
            this.id = id;
            let details = this.news[i];
            this.cover_img = details.cover;
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
            // this.progressLoader('show')
            let p = {
                key: 206,
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
                        // this.progressLoader('h')
                    }
                }
            );
        },

        uploadNews(){
            var error
            Object.keys(this.ff).forEach(i => {
                if ( this.ff[i].split(' ').join('') == "" ) {
                    this.error = i.charAt(0).toUpperCase() + i.slice(1) + ' is required'
                    error = this.error
                }
            })

            if ( error == null ) {
                this.fd = new FormData()
                if ( this.url && this.url.slice(0, 4) == 'blob' ) {
                    this.fd.set('cover', this.cover, this.ff.title+'|'+this.cover.name);
                    this.data.methods.postMethod(this.fd).then(
                        res => {
                            if ( res.data.code == 11 ) {
                                var cover_path = res.data.msg
                                this.submit(cover_path)
                            } else {
                                this.error = res.data.msg
                                // this.progressLoader('h')
                            }                           
                        }
                    )
                } else {
                    this.submit(null)
                }
            }
        },

        submit(cover_path){
            let p = {
                key: 205,
                title: this.ff.title,
                info: this.ff.info,
                uploaded_by: localStorage.getItem('user'),
                cover: cover_path,
                id: this.editMode ? this.id : null
            }
            this.data.methods.postMethod(p).then(
                res => {
                    if ( res.data.code == 11 ) {
                        this.onLoad();
                        this.clearFields();
                    } else {
                        this.error = res.data.msg;
                        // this.progressLoader('h')
                    }
                }
            )
        }
    }
}
</script>

<style src="./../styles/man-mus-styles.css" scoped></style>
<style scoped>
.modal-content {
    padding: 10px 20px;
    margin: auto;
}
</style>