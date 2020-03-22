<template>
  <form v-on:submit.prevent='login'>
    <h3>Admin Login</h3>
    <br>
    <p class="error">{{error}}</p>
    <br>
    <label>Email or Username</label>
    <br>
    <input placeholder='Enter email or username' v-model="user">
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
import router from './../router';

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
    login(){
      let p = {
        key: 201,
        user: this.user,
        password: this.password
      }
      this.dataMethods.postMethod(p).then(
        res => {
          if ( res.data.code === 11 ) {
            this.error = '';
            router.push( {name: 'man-mus'} );
          } else {
            this.error = res.data.msg;
          }
        }
      )
    }
  }
}
</script>

<style src="./../styles/admin-login-styles.css" scoped>

</style>
