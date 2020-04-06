<template>
  <div id="app">
    <Header/>
    <div class="route-content">
      <router-view/>
    </div>
    <footer>
      <section class="follow">
          <p> ( : </p>
      </section>
      <section class="year">
          <p>© 2020</p>
      </section>
    </footer>
  </div>
</template>

<script>
import Header from './components/Header.vue'

export default {
  name: 'App',
  components: {
    Header
  },
}
</script>

<style  src="./styles/style.css">

</style>
