<template>
    <div>
        <form class="sign" @submit.prevent='signup'>
            <h3>User Signup</h3>
            <br>
            <p class="error">{{error}}</p>
            <br>
            <label>Email <span>*</span></label>
            <br>
            <input placeholder='Enter your prefered email ' v-model="email">
            <br>
            <br>
            <label>Username <span>*</span></label>
            <br>
            <input placeholder='Enter your prefered username ' v-model="username">
            <br>
            <br>
            <label>Password <span>*</span></label>
            <br>
            <input type="password" placeholder='Enter Password' v-model="password">
            <br>
            <br>
            <label>Confirm Password <span>*</span></label>
            <br>
            <input type="password" placeholder='Re-enter password' v-model="cpassword">
            <br>
            <br>
            <div style="text-align: right">
            <button>Signup</button>
            </div>
            <br>
            <p class="form-footer">Already registered? <router-link :to="{name: 'login'}">Login</router-link></p>
        </form>
    </div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'
import store from './../vuexfile'

export default {
    name: 'Signup',
    data(){
        return {
            data: DataMixin,
            error: null,
            email: null,
            username: null,
            password: null,
            cpassword: null
        }
    },

    methods: {
        progressLoader(req){
            if ( req == 'show' ) {
                this.loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: true,
                    onCancel: this.onCancel,
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

        signup(){
            this.progressLoader('show')
            let p = {
                key: 103,
                email: this.email,
                username: this.username,
                password: this.password,
                cpassword: this.cpassword
            }
            this.data.methods.postMethod(p).then(
                res => {
                    if ( res.data.code == 11) {
                        this.error = ""
                        localStorage.setItem('user', p.email)
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