<template>
  <v-app>
    <Navbar />
    <router-view></router-view>
  </v-app>
</template>

<script>
import Navbar from '../components/AppBar.vue'

export default {
  components: { Navbar },
  name: 'App',
  data () {
    return {
      
    }
  }
}
</script>
