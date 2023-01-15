<template>
  <v-app id="app" class="grey lighten-4">

    <v-main>
      <router-view></router-view>
    </v-main>
    
  </v-app>
</template>

<script>
export default {
  name: 'App',
}
</script>

<style lang="scss">
    @import '../css/main.scss';
    @import url('https://fonts.googleapis.com/css?family=Material+Icons');
</style>