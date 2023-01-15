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
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                <v-card-title> Students </v-card-title>
                </v-col>
                <v-spacer></v-spacer>
                <!-- <v-col cols="12" md="5" class="mt-3 text-right">
                        <v-btn 
                            class="text-capitalize" 
                            color="success"
                            elevation="0" 
                            outlined
                            height="39"
                            @click="action({ action: 'addStudents'})"
                            >
                            <v-icon left> mdi-plus-box-outline </v-icon>
                            <span>Add Students</span>
                        </v-btn>
                    </v-col> -->
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.studentHeaders"
                :items="students"
                >
                 <template v-slot:[`item.actions`]="{ item, index }">
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
                    <!-- <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        v-on="on"
                        v-bind="attrs"
                        @click="removeStudent(item.id)"
                        >
                        mdi-delete-forever
                        </v-icon>
                    </template>
                    <span>Remove Student</span>
                    </v-tooltip> -->
                </template>
                
             </v-data-table>
        </v-card-text>
        <student-form 
          v-bind="studentForm"  
          @close="closeDialog"
          @edit="action({ ...studentForm, action: 'edit', show: true })"
        >
        </student-form>
         <grades-form 
          v-bind="gradesForm"  
          @close="closeDialog"
          @edit="action({ ...gradesForm, action: 'setGrades', show: true })"
        >
        </grades-form>
        <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import studentForm from './forms/student-form.vue';
import gradesForm from './forms/grades-form.vue';
import confirmForm from './forms/confirm-form.vue';
export default {
    name: 'section-details',
    components: {
        studentForm,
        confirmForm,
        gradesForm
    },
    // mixins:[LocalStorageMixins],
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
            studentForm: { show: false, action: '', data: {} },
            pageLimit: 10,
            pageSelection:[10,20,50,100],
            page: 1,
            totalPage:0,
            search:'',
            timer:'',
            isLoading:false,
            showPagination: false,
            studentForm: { show: false, action: '', data: {} },
            gradesForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
            subject:'',
            section:'',
            instructor:'',
            schedule:''
        }
    },
    computed: {
        // ...mapState("teacher",["teacher","classes","message"]),
        ...mapState("classList",["class","classes","message","students"]),
        // subject(){
        //     return `${this.$route.params.subject}`
        // },
        // section(){
        //     return `${this.$route.params.section}`
        // },
        // instructor(){
        //     return `${this.$route.params.instructor}`
        // },
    },
    watch:{
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
    },
    mounted(){
      
      this.initialize()
       
    },
    methods: {
        ...mapActions("classList",["GET_CLASS_STUDENTS"]),
        initialize(){
            this.GET_LOCALSTORAGE_DATA()
            this.get_class_data()
        },
        get_class_data(){
            let payload = {
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
                case 'addSubjects':
                this.studentForm = {show: true, action, data: this.section}
                break
                case 'addStudents':
                this.studentForm = {show: true, action, data: {id: this.$route.params.id}}
                break
                case 'setGrades':
                    this.gradesForm = { show: true, action, data: data, index}
                    // console.log({action, data, index, message})
                break;
                case 'view':
                this.studentForm = { show: true, action, data , index}
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
            this.studentForm = { show: false, action: '', data:{} },
            this.gradesForm = { show: false, action: '', data:{} }
            this.confirmForm = { show: false, action: '', data:{} }
            // this.GET_CLASSES(this.$route.params.id)
        },
        // removeStudent(id){
        //     this.$store.dispatch("classList/REMOVE_CLASS_STUDENT", id)
        // },
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
