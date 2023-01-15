<template>
   <div id="app-bar">
    <v-app-bar
        app
        color="red darken-4"
        outlined
        height="80"
    >
      <v-app-bar-nav-icon dark @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title class="white--text">
          {{ title }}
      </v-toolbar-title>
      
      <v-spacer></v-spacer>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
            <v-btn 
              v-on="on" 
              v-bind="attrs" 
              text
              x-large
              dark
              class="text-capitalize"
              >
              <v-icon
                left
                dark
              >
                mdi-account
              </v-icon>
              Welcome {{ name }}
              <v-icon
                right
                dark
              >
                mdi-menu-down
              </v-icon>
            </v-btn>
        </template>
        <v-list style="max-height: 150px" class="overflow-y-auto">
          <v-list-item
            active-class="blue lighten-5 blue--text"
            v-for="(item, index) in profileMenu"
            :key="index"
            @click="handleAction(item.action)"
          >
            <v-icon style="padding-right: 10px">{{ item.icon }}</v-icon>
            {{ item.text }}
          </v-list-item>
        </v-list>
      </v-menu>
    
    </v-app-bar>
    <v-navigation-drawer
        app
        v-model="drawer"
        color="#B71C1C"
        dark
        
    >
      <v-list
      >
        <v-list-item-group>
          <v-list-item
            v-for="link in links"
            :key="link.text"
            router
            :to="link.route"
          >
            <v-list-item-action>
                <v-icon>{{ link.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <v-list-item-title>{{ link.text }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-navigation-drawer>
    
  </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'

export default {
    name:'appbar',
    data(){
        return{
            drawer: true,
            adminLinks: [
                {
                    icon: "mdi-monitor-dashboard",
                    text: "Dashboard",
                    route: "/dashboard",
                },
                {
                    icon: 'mdi-school',
                    text: "School Year",
                    route: "/admin/school-year",
                },
                {
                    icon: "mdi-calendar-outline",
                    text: "Class",
                    route: "/admin/class",
                },
                {
                    icon: "mdi-table-account",
                    text: "Sections",
                    route: "/admin/sections",
                },
                {
                    icon: "mdi-bookshelf",
                    text: "Subjects",
                    route: "/admin/subjects",
                },
                {
                    icon: "mdi-account-tie-outline",
                    text: "Teachers",
                    route: "/admin/teachers",
                },
                {
                    icon: "mdi-account-group-outline",
                    text: "Students",
                    route: "/admin/students",
                },
                {
                      icon: "mdi-account",
                      text: "My Profile",
                      route: "/admin/profile",
                  },
              ],
              teacherLinks: [
                // {
                //     icon: "mdi-monitor-dashboard",
                //     text: "Dashboard",
                //     route: "/dashboard",
                // },
                // {
                //     icon: "mdi-calendar-outline",
                //     text: "Class",
                //     route: "/class",
                // },
                  {
                      icon: "mdi-bookshelf",
                      text: "Classes",
                      route: "/teacher/classes",
                  },
                  {
                      icon: "mdi-account",
                      text: "My Profile",
                      route: "/teacher/profile",
                  },
                ],
                studentLinks: [
                // {
                //     icon: "mdi-monitor-dashboard",
                //     text: "Dashboard",
                //     route: "/dashboard",
                // },
                 
                  {
                      icon: "mdi-bookshelf",
                      text: "Class",
                      route: "/student/classes",
                  },
                  {
                      icon: "mdi-calendar-outline",
                      text: "Grades",
                      route: "/student/grades",
                  },
                  {
                      icon: "mdi-account",
                      text: "My Profile",
                      route: "/student/profile",
                  },
                ],
              profileMenu:[
                // { text: "Account", icon: "mdi-account-circle-outline" },
                { text: "Sign out", icon: "mdi-logout-variant", action: "logout" },
              ],
              rules:{
                emailRules: [
                    value=> !! value || "Email Address is required",
                    value => this.emailRegex.test(value) || 'E-mail must be valid',
                ],
                passwordRules:[
                    value =>  !! value || "Password is required",
                    // value => this.passwordRegex.test(value) || "Password is invalid.",
                ],
              },
              emailRegex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
              passwordRegex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/,
        }
    },
    computed:{
        ...mapState("auth",["user"]),
        title(){
            return `${process.env.MIX_SYSTEM_NAME}`
        },
        name(){
          let name = ''
          if(this.user){
            return this.user.first_name
          }
          return name 
        },
        links(){
          let links
          switch(this.user.role){
            case('admin'):
              links = this.adminLinks
            break
            case('teacher'):
              links = this.teacherLinks
            break
            case('student'):
              links = this.studentLinks
            break
          }
          return links
        }
    },
    mounted(){
      this.GET_SY_SELECTION(),
      this.GET_GRADE_LEVEL_SELECTION()
      this.GET_USER_PROFILE()

    setTimeout(()=>{
      let loc = window.location.href
      if(this.user.role == "admin"){
        let path = '/dashboard'
        if (this.$route.path !== path)
        this.$router.push(path)
      }
      if(this.user.role == "teacher"){
        let path = '/teacher/classes'
       if (this.$route.path !== path)
        this.$router.push(path)
      }
      if(this.user.role == "student"){
        let path = '/student/classes'
        if (this.$route.path !== path)
        this.$router.push(path)
      }
    }, 3000)
      
    },
    methods:{
      ...mapActions("auth",["GET_USER_PROFILE"]),
      ...mapActions("classList",["GET_SY_SELECTION","GET_GRADE_LEVEL_SELECTION"]),
      handleSubmit(){
        let formStatus = this.$refs.userForm.validate()
      },
      handleAction(item) {
        switch (item) {
          case "logout":
            this.$store.dispatch("auth/logout");
            break;
          case "Profile":
            console.log("Profile");
            break;
          case "Logout":
            console.log("Logout");
        }
      },
    }
}
</script>
<style lang="scss">
#app-bar{
  // .v-application .error--text{
  //   color: #fff !important;
  //   caret-color: #fff !important;
  // }
  .v-list-item {
    height: 70px;
  }
  
}
//  .v-messages__message{
//     color: #fff !important;
//     caret-color: #fff !important;
//   }
</style>
