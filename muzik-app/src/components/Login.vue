<template>
    <div>
        <form class="sign" @submit.prevent='login'>
            <h3>User Login</h3>
            <br>
            <p class="error">{{error}}</p>
            <br>
            <label>Email or Username <span>*</span></label>
            <br>
            <input placeholder='Enter email or username' v-model="user">
            <br>
            <br>
            <label>Password <span>*</span></label>
            <br>
            <input type="password" placeholder='Enter Password' v-model="password">
            <br>
            <br>
            <div style="text-align: right">
                <button>Login</button>
            </div>
            <br>
            <p class="form-footer">Don't have an account yet? <router-link :to="{name: 'signup'}">Signup</router-link></p>
        </form>
    </div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'
import store from './../vuexfile'

export default {
    name: 'Login',

    data(){
        return {
            loader: null,
            data: DataMixin,
            error: null,
            user: null,
            password: null
        }
    },

    methods: {
        progressLoader(req){
            if ( req == 'show' ) {
                this.loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: true,
                    transition: 'fade',
                    color: '#1d5bce',
                    loader: 'dots',
                    backgroundColor: '#000',
                    opacity: .9,
                });
            } else {
                this.loader.hide()
            }
        },

        login(){
            this.progressLoader('show')
            let p = {
                key: 104,
                user: this.user,
                password: this.password
            }
            this.data.methods.postMethod(p).then(
                res => {
                    if ( res.data.code == 11) {
                        this.error = ""
                        localStorage.setItem('user', p.user)
                        this.progressLoader('h')
                        store.commit('checkSessionStatus')
                        this.$router.push({name: 'home'})
                    } else {
                        this.error = res.data.msg
                        this.progressLoader('h')
                    }
                }
            )
        }
    }
}
</script>