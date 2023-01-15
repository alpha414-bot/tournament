<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-text>
             <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>
                
                <v-col cols="12" md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize"
                        color="green" 
                        outlined
                        elevation="0" 
                        height="39"
                         @click="action({ action: 'edit' })"
                        >
                        <v-icon left> mdi-account-edit </v-icon>
                        <span>Edit Profile</span>
                    </v-btn>
                </v-col>
            </v-row>        
        </v-card-text>
        <v-row>
            <v-col cols="12" md="6">
                
                <v-card-title class="title">
                    <span>Account Information</span>
                </v-card-title>
               
                <v-card-text>
                    <v-row>
                        <v-col
                            cols="12"
                            >
                            <v-text-field
                                v-model="user.instructor_number"
                                label="Instructor Number"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.first_name"
                                label="First Name"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.last_name"
                                label="First Name"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.birth_date"
                                label="Birth Date"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.email"
                                label="Email"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.mobile"
                                label="Mobile Number"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        
                        <v-col
                            cols="12"
                            md="6"
                            >
                            <v-text-field
                                v-model="user.address"
                                label="Address"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-col>
            <v-col cols="12" md="6">
                 <v-card-title class="title">
                    <span>Contact Details</span>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col
                            cols="12"
                            >
                            <v-text-field
                                v-model="user.contact_name"
                                label="Contact Name"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            >
                            <v-text-field
                                v-model="user.contact_number"
                                label="Contact Number"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            >
                            <v-text-field
                                v-model="user.contact_relationship"
                                label="Relationship"
                                outlined
                                shaped
                                :readonly="true"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-col>
        </v-row>
        <teacher-form 
          v-bind="teacherForm"  
          @close="closeDialog"
          @edit="action({ ...teacherForm, action: 'edit', show: true })"
        >
        </teacher-form>
    </v-card>
</template>

<script>
import {mapState, mapGetters, mapActions, } from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import teacherForm from './forms/teacher-form.vue';
export default {
    name:'class',
    components:{
          teacherForm,
    },
    mixins: [SelectionMixins],
    data(){
        return{
       
           teacherForm: { show: false, action: '', data: {} },
        }
    },
    computed:{
        ...mapState("auth",["user"])
    },
    watch:{
       
    },
    mounted(){
       
    },
    methods:{
        ...mapActions("auth",["GET_USER_PROFILE"]),
        action({action, data, index, message}){

            switch(action){
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.teacherForm = { show: true, action, data: this.user, index: this.user.id}
                break;
                
            }
        },
        closeDialog(){
        teacherForm = { ...teacherForm, show: false, data: {}}
        // setTimeout(()=>{ this.$router.go(this.$router.currentRoute) }, 2000)
        this.GET_USER_PROFILE()
        }
    },
    
}
</script>

<style lang="scss" scoped>
#main-page{
    .add-button {
    height: 39px;
    background-color: #04c35c;
    text-transform: none;
    // width: 168px;
    // font-size: 12px;
    // font-weight: 400;
    color: #fff;
    .v-icon {
      color: #fff;
    }
  }
}
</style>