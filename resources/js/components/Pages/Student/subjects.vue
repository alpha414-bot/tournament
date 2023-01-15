<template>
      <v-card id="main-page" class="pa-5" height="100%">
        <v-btn class="pl-0" plain text @click="goBack()"><v-icon>mdi-chevron-left</v-icon> Back</v-btn>
        <v-card-text>
             <v-row>
                <v-col cols="3" >
                    <v-card-subtitle> Subject: {{subject}}</v-card-subtitle>
                </v-col>
                <v-col cols="3">
                    <v-card-subtitle> Section: {{section}}</v-card-subtitle>
                </v-col>
                <v-col cols="3">
                    <v-card-subtitle> Instructor: {{instructor}}</v-card-subtitle>
                </v-col>
                <v-col cols="3">
                    <v-card-subtitle> Schedule: {{schedule}}</v-card-subtitle>
                </v-col>
            </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-title> List of Students </v-card-title>
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
                    </v-row>
                </v-col>
               
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.studentHeaders"
                :items="students"
                >
                <template v-slot:item.actions="{ item , index }">
                 <v-tooltip bottom>
                   <template v-slot:activator="{ on, attrs }">
                            <v-icon
                            class="pl-3"
                            v-on="on"
                            v-bind="attrs"
                            @click="action({ action: 'setGrades', data: item, index })"
                            >
                            mdi-playlist-edit
                            </v-icon>
                        </template>
                        <span> View grades </span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
       <grades-form 
          v-bind="gradesForm"  
          @close="closeDialog"
          @edit="action({ ...gradesForm, action: 'setGrades', show: true })"
        >
        </grades-form>
    </v-card>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import classForm from './forms/class-form.vue';
import gradesForm from './forms/grades-form.vue';
import LocalStorageMixins from '../../../mixins/localStorage.js'
export default {
    name: 'student-details',
    components: {
        classForm,
        gradesForm
    },
    mixins:[LocalStorageMixins],
    data() {
        return {
            dataTable:{
                 studentHeaders:[
                    {text:'ID',value:'learners_number'},
                    {text:'First Name',value:'first_name',cellClass:'text-capitalize'},
                    {text:'Last Name',value:'last_name', cellClass:'text-capitalize'},
                    {text:'1st',value:'first'},
                    {text:'2nd',value:'second'},
                    {text:'3rd',value:'third'},
                    {text:'4th',value:'fourth'},
                    {text:'Total Average',value:'total_average'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}

                ],
                data:[]
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
            gradesForm: { show: false, action: '', data: {} },
            classForm: { show: false, action: '', data: {} },
            enrollmentForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
            school_year: '',
            subject:'',
            section:'',
            instructor:'',
            schedule:''
        }
    },
    computed: {
        ...mapState("classList",["class","classes","message","students"]),
        ...mapState("auth",["user"]),
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
            // console.log(to);
        },
        student(){
            this.SET_DATA()
        },
        get_sy_selection(to){
            this.school_year = to.slice(0, 1).shift().value
        },
        school_year(to){
            
        }
    },
    mounted(){
        this.init()
        
    },
    methods: {
      ...mapActions("classList",["GET_CLASS_STUDENTS"]),
        init(){
            this.GET_LOCALSTORAGE_DATA()
            this.SET_DATA()
            console.log("TEACHER ACCOUNT", this.user.id, this.user.first_name)
            this.get_class_data()
        },
        get_class_data(){
            let payload = {
                // teacher_id: this.user.id,
                class_id: this.$route.params.id
            }
            this.GET_CLASS_STUDENTS(payload)
            // this.GET_CLASSES(this.$route.params.id)
        },
        goBack(){
            this.$router.go(-1)
        },
        action({action, data, index, message}){

            switch(action){
                case 'add':
                
                this.classForm = {show: true, action, data:{}}
                break
                case 'setGrades':
                    this.gradesForm = { show: true, action, data: data, index}
                    // console.log({action, data, index, message})
                break;
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
        
        closeDialog(){
            this.gradesForm = { show: false, action: '', data:{} }
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
        },
        GET_LOCALSTORAGE_DATA(){
            let data = localStorage.getItem('class') ? JSON.parse(localStorage.getItem('class')) : ''
            this.subject = data.subject
            this.section = data.section
            this.instructor = data.instructor
            this.schedule = data.start_time + " - " + data.end_time
        },

    },
}
</script>

<style lang="scss" scoped>
// @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

</style>
