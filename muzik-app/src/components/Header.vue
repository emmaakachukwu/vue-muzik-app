<template>
  <div>
    <header>
      <a class="logo" href="#">VUE-MUZIK</a>
      <nav class="main-nav">      
        <a class="menu-btn" href="#">MENU</a>
        <ul>                  
            <li><router-link :to="{name: 'home'}" class="link" active-class="active">HOME</router-link></li>
            <li v-if="!session"><router-link :to="{name: 'login'}" class="link" active-class="active">LOGIN</router-link></li>
            <li v-if="!session"><router-link :to="{name: 'signup'}" class="link" active-class="active">SIGNUP</router-link></li>
            <li><a href="#" class="link">CONTACT</a></li>
            <li id="logout" v-if="session" @click="logOut"><a class="link">LOGOUT</a></li>
        </ul>
      </nav>
    </header>
    <nav class="sub-nav">
      <ul>
        <li class="center"><a href="#">Link A</a></li>
        <li class="center"><a href="#">Link B</a></li>
        <li class=""><a href="#">Link C</a></li>
      </ul>
    </nav>
  </div>
</template>

<script>
import { DataMixin } from './../mixins/datamixin'
import store from './../vuexfile'
export default {
  name: 'Header',

  computed: {
    session(){
      return store.state.session
    }
  },

  data(){
    return {
      data: DataMixin,
    }
  },

  methods: {
    logOut(){
      localStorage.clear()
      store.commit('checkSessionStatus')
    }
  }
}
</script>

<style scoped>
header {
  display: block;
  width: 100%;
  height: 50px;
  background: #1d5bce;
  box-sizing: border-box;
  padding: 0 60px;
}

.main-nav {
  float: right;
  display: block;
}

.sub-nav {
  display: block;
  background: #000;
  width: 100%;
  height: 40px;
  position: sticky;
  top: 0;
  z-index: 1;
  border-bottom: 2px solid #fff;
}

.sub-nav ul {
  /* float: right; */
  line-height: 40px;
}

.main-nav ul li, .menu-btn {
  display: inline-block;
  line-height: 50px;
}

.main-nav ul li, .sub-nav ul li {
  padding: 0 0 0 20px;
}

.logo {
  display: inline;
  text-align: left;
  line-height: 50px;
  font-weight: bold;
  letter-spacing: 3px;
}

a, #logout {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}

ul li a:hover, .active {
  color: #C8C8C8;
  border-bottom: 1px solid #C8C8C8;
}

.menu-btn {
  float: right;
  display: none;
}

.sub-nav ul li {
  display: inline-block;
}

@media ( max-width: 768px ) {
  .main-nav ul {
    display: none;
    background: #1d5bce;
    width: 40%;
    border: 1px solid #888;
    z-index: 1000;
    position: absolute;
    right: 0;
    top: 50px;
    border-radius: 10px 0;
  }

  .main-nav ul li {
    text-align: center;
  }

  /* .main-nav ul li a:hover {
    color: #FFF;
  }

  .main-nav ul li:hover {
    background: #000;
  } */

  .main-nav ul li {
    display: block;
    width: 100%;
  }

  .menu-btn {
    display: inline-block;
    text-decoration: none;
    text-align: right;
  }

  .main-nav:hover ul {
    display: block;
  }

}
</style>