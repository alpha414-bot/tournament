<template>
   
    <div id="app-bar">
    <v-app-bar
        app
        color="#B71C1C"
        outlined
        height="80"
    >
      <!-- <v-app-bar-nav-icon dark @click.stop="drawer = !drawer"></v-app-bar-nav-icon> -->

      <v-toolbar-title class="white--text">
          {{ title }}
      </v-toolbar-title>

      <v-spacer></v-spacer>
       <!-- login error -->
          <template v-if="isError">
            <v-alert
              class="mt-3"
              color="white"
              dense
              outlined
              type="error">
              {{ message }}
            </v-alert>
          </template>
          <!-- /Login error -->
      <v-form ref="userForm">
          <v-container class="mt-5" height="80">
              <v-row cols="12">
                  <v-col md="4">
                       <v-text-field
                        v-model="formData.email"
                        label="Enter email"
                        
                        flat
                        outlined
                        dense
                        :rules="rules.emailRules"
                        ></v-text-field>
                  </v-col>
                  <v-col md="4">
                      <v-text-field
                        v-model="formData.password"
                        label="Enter password"
                        
                        flat
                        outlined
                        dense
                        :rules="rules.passwordRules"
                        type="password"
                        ></v-text-field>
                  </v-col>

                    <v-col md="4">
                        <v-btn-toggle>
                            <v-btn color="success" depressed height="39" @click="handleSubmit">
                                <span class="text-capitalize">Sign In</span>
                            </v-btn>
                            <v-btn color="info" depressed height="39" @click="showForm">
                                <span class="text-capitalize">Register</span>
                            </v-btn>
                        </v-btn-toggle>
                    </v-col>
                  <!-- <v-col md="2">
                      <v-btn
                        color="success"
                        depressed
                        height="39"
                        @click="handleSubmit"
                      >
                      <span class="text-capitalize">Sign In</span>
                      </v-btn>
                  </v-col>
                <v-col md="2">
                    <v-btn color="success" depressed height="39"><span class="text-capitalize">Join</span></v-btn>
                </v-col> -->
              </v-row>
          </v-container>
      </v-form>
        
    </v-app-bar>
    <v-card flat class="pa-10">
            <!-- <v-card-title height="300" class="grey lighten-4 mx-auto" >
            
            </v-card-title> -->
            <v-row>
                <v-col cols="12">
                    <v-img
                    :src="'./assets/homepage.png'"
                    contain
                    max-height="150"
                    position="left center"
                    ></v-img>
                </v-col>
            </v-row>
            
    </v-card>
    <v-divider></v-divider>
    <v-card flat class="mt-5 pa-5">
    <v-row>
            <v-col>
                <v-card flat>
                    <div class="text-center">
                        <v-icon size="300">mdi-account-group</v-icon>
                    </div>
                    <v-card-title>Student Module</v-card-title>
                    <v-card-text>
                        Student will login using email and password to view their grades
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <v-card flat>
                    <div class="text-center">
                        <v-icon size="300">mdi-table</v-icon>
                    </div>
                    <v-card-title>Admin Module</v-card-title>
                    <v-card-text>
                        Administrator Module can manage the students and faculty information
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col >
                <v-card flat>
                    <div class="text-center">
                        <v-icon size="300">mdi-view-list-outline</v-icon>
                    </div>
                    <v-card-title>Faculty Module</v-card-title>
                    <v-card-text>
                        Faculty Module will be able to view their assigned class and students
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <register-form v-bind="registerForm" @close="closeForm" @edit="show({show:true})"></register-form>
    </v-card>
       
    </div>
    
</template>

<script>
import { mapState } from "vuex";
import registerForm from './register.vue';
export default {
    name:'login',
    components: {
        registerForm,
    },
    data(){
        return{
            drawer: true,
            links: [
                {
                    icon: "mdi-home-city-outline",
                    text: "Dashboard",
                    route: "/dashboard",
                },
                {
                    icon: "mdi-home-city-outline",
                    text: "Dashboard",
                    route: "/dashboard",
                },
                {
                    icon: "mdi-home-city-outline",
                    text: "Dashboard",
                    route: "/dashboard",
                },
            ],
            formData:{
                email:'',
                password:''
            },
            registerForm: {show: false},
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
        ...mapState("auth", ["isError","message"]),
        title(){
            return `${process.env.MIX_SYSTEM_NAME}`
        }

    },
    methods:{
        handleSubmit(){
            let formStatus = this.$refs.userForm.validate()

            if(formStatus){
                // let {email, password} = this.formData
                this.$store.dispatch("auth/login", this.formData)  
            }
        },
        showForm(){
            this.registerForm = {show: true};
        },
        closeForm(){
            this.registerForm.show = false
        }
    }
}
</script>

<style lang="scss" scoped>

</style>