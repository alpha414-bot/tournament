<template>
      <v-card id="main-page" class="pa-5" height="100%">
        <v-btn class="pl-0" plain text @click="goBack()"><v-icon>mdi-chevron-left</v-icon> Back</v-btn>
        <v-card-title class="title">
            <span>Student's Information</span>
            <v-spacer></v-spacer>
            <v-btn  
            color="green" 
            width="150"
            outlined
            elevation="0" 
            height="39"
            @click="action({ action: 'edit'})">
            <v-icon left>mdi-account-edit</v-icon> 
            <span class="text-capitalize">Edit Profile</span>
            </v-btn>
        </v-card-title>
        <v-card-text>
           <v-row >
               <v-col cols="12">
                   <v-card-subtitle>ID Number :  {{student.learners_number}}</v-card-subtitle>
                    <v-card-subtitle class="text-capitalize">Name :  {{fullName}}</v-card-subtitle>
               </v-col>
           </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-title> Class Records </v-card-title>
        <v-card-text>
             <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                        <!-- <v-col cols="12" md="4">
                            <v-text-field
                            v-model="search"
                            placeholder="Search subject"
                            prepend-inner-icon="mdi-magnify"
                            hide-details
                            outlined
                            dense
                            >
                            </v-text-field>
                        </v-col> -->
                         <v-col cols="12" md="4">
                            <v-select
                            v-model="school_year"
                            :items="get_sy_selection"
                            item-text="key"
                            item-value="value"
                            label="Select School Year"
                            hide-details
                            outlined
                            dense
                            @change="get_enrollments_data"
                            >
                            </v-select>
                        </v-col>
                    </v-row>
                </v-col>
                
                <!-- <v-spacer></v-spacer>
                <v-col cols="12" md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize" 
                        color="success"
                        elevation="0" 
                        outlined
                        height="39"
                         @click="enroll({ action: 'add' })"
                        >
                        <v-icon left> mdi-plus-box-outline </v-icon>
                        <span>New enrollment</span>
                    </v-btn>
                </v-col> -->
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="grades"
                >
                <template v-slot:item.actions="{ item }">
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        
                        class=""
                        v-on="on"
                        v-bind="attrs"
                        @click="viewDetails(item.id)"
                        >
                        mdi-account-eye
                        </v-icon>
                    </template>
                    <span>View Account </span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
        <class-form 
          v-bind="classForm"  
          @close="classForm = { ...classForm, show: false, data: {}}"
          @edit="action({ ...classForm, action: 'edit', show: true })"
        >
        </class-form>
        <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import classForm from './forms/student-form.vue';
import confirmForm from './forms/confirm-form.vue';
import LocalStorageMixins from '../../../mixins/localStorage.js'
export default {
    name: 'student-details',
    components: {
        classForm,
        confirmForm,
    },
    mixins:[LocalStorageMixins],
    data() {
        return {
            dataTable:{
                headers:[
                    {text:'Section',value:'section'},
                    {text:'Subject',value:'subject',cellClass:'text-capitalize'},
                    {text:'Instructor',value:'instructor'},
                    {text:'1st',value:'first'},
                    {text:'2nd',value:'second'},
                    {text:'3rd',value:'third'},
                    {text:'4th',value:'fourth'},
                    {text:'Total Average',value:'total_average'},
                    
                ]
            },
            classForm: { show: false, action: '', data: {} },
            pageLimit: 10,
            pageSelection:[10,20,50,100],
            page: 1,
            totalPage:0,
            search:'',
            timer:'',
            isLoading:false,
            showPagination: false,
            classForm: { show: false, action: '', data: {} },
            enrollmentForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
            school_year: 'all',
        }
    },
    computed: {
        ...mapState("student",["student","classes","message","grades"]),
        ...mapGetters("classList",["get_sy_selection"]),
        fullName(){
            return `${this.student.first_name} ${this.student.last_name}`
        },
    },
    watch:{
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
        assignedSubjects(to){
        },
        student(){
            this.SET_DATA()
        },
        get_sy_selection(to){
            console.log(to)
            this.school_year = to.slice(0, 1).shift().value
        },
        school_year(to){
            this.get_enrollments_data()
        }
    },
    mounted(){
        this.init()
        
    },
    methods: {
      ...mapActions("student", ['GET_STUDENT','GET_GRADES']),
        init(){
            this.GET_STUDENT(this.$route.params.id)
            this.get_enrollments_data()
            this.SET_DATA()
        },
        get_enrollments_data(){
            let payload = {
                student_id: this.$route.params.id
            }
             if(this.school_year !== 'all' || this.school_year === ''){
                payload.school_year_id = this.school_year
            }
            if(payload.student_id){
                this.GET_GRADES(payload)
            }
        },
        goBack(){
            this.$router.go(-1)
        },
        action({action, data, index, message}){

            switch(action){
                case 'add':
                
                this.classForm = {show: true, action, data:{}}
                break
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.classForm = { show: true, action, data: this.student, index: this.student.id}
                break;
                case 'view':
                this.classForm = { show: true, action, data , index}
                break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index, message};
                break
            }
        },
         enroll({action, data, index, message}){

            switch(action){
                case 'add':
                
                this.enrollmentForm = {show: true, action, data:this.student}
                break
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.enrollmentForm = { show: true, action, data: this.student, index: this.student.id}
                break;
                case 'view':
                this.enrollmentForm = { show: true, action, data , index}
                break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index, message};
                break
            }
        },
        closeDialog(){
            this.confirmForm = { show: false, action: '', data:{} }
        },
        viewDetails(id)
        {
          const path = `/admin/students/${id}/subjects`
          this.$router.push(path)
        },
        SET_DATA(){
        let data = this.student
        this.SET_LOCALSTORAGE_DATA('student', data)
        }

    },
}
</script>

<style lang="scss" scoped>
// @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

</style>
