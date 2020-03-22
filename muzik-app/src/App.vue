<template>
  <div id="app">
    <Header/>
    <div class="route-content">
      <router-view/>
    </div>
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
