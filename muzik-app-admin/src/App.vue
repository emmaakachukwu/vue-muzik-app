<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<script>

export default {
  name: 'app'
}

</script>

<style>

* {
  margin: 0;
  padding: 0;
  font-family: ubuntu;
}

input:focus, button:focus, textarea:focus {
  outline: none;
}

input:-webkit-autofill {
  box-shadow: 0 0 0 1000px #fff inset;
}

li {
  list-style-type: none;
}

button {
  cursor: pointer;
  border: none;
}

.error {
  color: #8b2222;
  text-align: center;
  font-style: italic;
  font-size: 13px;
}

</style>