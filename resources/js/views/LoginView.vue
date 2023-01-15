<template>
  <Login />
</template>

<script>
import Login from '../components/Pages/Login'
export default {
  components: {
    Login,
  },
//   beforeRouteEnter(to, from, next) {
//     next(vm => {
//       if (vm.$store.state.auth.authToken !== null) {
//         vm.$router.push("/dashboard");
//       }
//     });
//   }
}
</script>