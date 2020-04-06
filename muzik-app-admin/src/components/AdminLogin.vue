<template>
  <form v-on:submit.prevent='login'>
    <h3>Admin Login</h3>
    <br>
    <p class="error">{{error}}</p>
    <br>
    <label>Email</label>
    <br>
    <input type="email" placeholder='Enter email address' v-model="user">
    <br>
    <br>
    <label>Password</label>
    <br>
    <input type="password" placeholder='Enter Password' v-model="password">
    <br>
    <br>
    <div style="text-align: right">
      <button>Login</button>
    </div>
  </form>
</template>

<script>
import { DataMixin } from "./../mixins/datamixin";

export default {
  name: 'AdminLogin',
  mixins: [DataMixin],
  data(){
    return {
      dataMethods: DataMixin.methods,
      user: '',
      password: '',
      error: ''
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
        key: 201,
        user: this.user,
        password: this.password
      }
      this.dataMethods.postMethod(p).then(
        res => {
          if ( res.data.code === 11 ) {
            this.error = "";
            localStorage.setItem('user', p.user)
            this.progressLoader('h')
            this.$router.push( {name: 'man-mus'} );
          } else {
            this.error = res.data.msg;
            this.progressLoader('h')
          }
        }
      )
    }
  }
}
</script>

<style src="./../styles/admin-login-styles.css" scoped>

</style>
