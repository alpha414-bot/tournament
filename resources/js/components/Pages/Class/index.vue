<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>Class Subjects</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                            <v-col  cols="12" md="4">
                                <v-select
                                v-model="school_year_id"
                                :items="sy_selection"
                                item-text="key"
                                item-value="value"
                                label="Select school year"
                                outlined
                                dense
                                
                                flat
                                ></v-select>
                            </v-col>
                            <v-col  cols="12" md="4">
                                <v-select
                                v-model="grade_level_id"
                                :items="grade_level_selection"
                                item-text="key"
                                item-value="value"
                                label="Select grade level"
                                outlined
                                dense
                                
                                flat
                                @change="get_sections_subjects_data"
                                ></v-select>
                            </v-col>
                            <v-col  cols="12" md="4">
                                <v-select
                                v-model="section_id"
                                :items="sections_selection"
                                item-text="key"
                                item-value="value"
                                label="Select section"
                                outlined
                                dense
                                
                                flat
                                ></v-select>
                            </v-col>
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
                         @click="action({ action: 'add' })"
                        >
                        <v-icon left> mdi-clipboard-list-outline </v-icon>
                        <span>Add Class</span>
                    </v-btn>
                </v-col>
                <v-col cols="12"  md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize"
                        color="green" 
                        outlined
                        elevation="0" 
                        height="39"
                         @click="action({ action: 'addStudents' })"
                        >
                        <v-icon left> mdi-clipboard-list-outline </v-icon>
                        <span>Add Students</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="classes"
                :items-per-page="5"
                >
                <template v-slot:[`item.actions`]="{ item, index }">
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        small
                        class="mr-3"
                        v-on="on"
                        v-bind="attrs"
                        @click="action({ action: 'updateSchedule', data: item, index })"
                        >
                        mdi-pencil
                        </v-icon>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        small
                        class="mr-3"
                        v-on="on"
                        v-bind="attrs"
                        @click="viewDetails(item)"
                        >
                        mdi-eye
                        </v-icon>
                    </template>
                    <span>View Students</span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
        <v-card-title class="title">
            <span>Class Students</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col  cols="4">
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                            v-model="search"
                            placeholder="Search ID / Name"
                            prepend-inner-icon="mdi-magnify"
                            hide-details
                            outlined
                            dense
                            >
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>
                
                
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
                        v-on="on"
                        v-bind="attrs"
                        @click="removeStudent(item,index)"
                        >
                        mdi-delete-forever
                        </v-icon>
                    </template>
                    <span>Remove Student</span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
        <student-form 
          v-bind="studentForm"  
          @close="closeForm"
          @edit="action({ ...studentForm, action: 'edit', show: true })"
        >
        </student-form>
        <class-form 
          v-bind="classForm"  
          @close="closeForm"
          @edit="action({ ...classForm, action: 'edit', show: true })"
        >
        </class-form>
        <schedule-form 
          v-bind="scheduleForm"  
          @close="closeForm"
          @edit="action({ ...scheduleForm, action: 'updateSchedule', show: true })"
        >
        </schedule-form>
        <confirm-form v-bind="confirmForm" @close="closeForm"></confirm-form>
    </v-card>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import studentForm from './forms/students-form.vue';
import classForm from './forms/class-form.vue';
import scheduleForm from './forms/schedule-form.vue';
import confirmForm from './forms/confirm-form.vue'
import LocalStorageMixins from '../../../mixins/localStorage.js'
export default {
    name:'class',
    components:{
        studentForm,
        classForm,
        scheduleForm,
        confirmForm,
    },
    mixins: [SelectionMixins, LocalStorageMixins],
    data(){
        return{
            search:'',
            school_year_id:'',
            grade_level_id:'',
            section_id:'',
            dataTable:{
                headers:[
                    {text:'Section',value:'section'},
                    {text:'Subject',value:'subject',cellClass:'text-capitalize'},
                    {text:'Days',value:'days'},
                    {text:'Start time',value:'start_time'},
                    {text:'End time',value:'end_time'},
                    {text:'Instructor',value:'instructor'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}
                    
                ],
                studentHeaders:[
                    {text:'ID',value:'learners_number'},
                    {text:'First Name',value:'first_name',cellClass:'text-capitalize'},
                    {text:'Last Name',value:'last_name', cellClass:'text-capitalize'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}
                ]
            },
            studentForm: { show: false, action: '', data: {} },
            classForm: { show: false, action: '', data: {} },
            scheduleForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data: {} },
        }
    },
    computed:{
         ...mapState("classList",["classes","students","sy_selection","grade_level_selection","sections_selection","subjects_selection", "message"]),
    },
    watch:{
        search(){
            setTimeout(()=>{
               this.GET_STUDENTS_DATA()
            },600)
        },
        school_year_id(to){
            this.GET_CLASSES_DATA()
            this.GET_STUDENTS_DATA()
        },
        grade_level_id(to){
           this.GET_CLASSES_DATA()
           this.GET_STUDENTS_DATA()
        },
        section_id(to){
           this.GET_CLASSES_DATA()
           this.GET_STUDENTS_DATA()
        },
        sy_selection(data){
            this.school_year_id = data.at(-1).value
        },
        grade_level_selection(data){
            this.grade_level_id = data[0].value
            this.get_sections_subjects_data()
        },
        sections_selection(data){
            if( this.grade_level_id !== ''){
                this.section_id = data[0].value
            }
        },
        message(message){
            if(message!=''){
                console.log(message)
                this.action({action: 'confirm', data:{}, index: '', message})
            }
        }
    },
    mounted(){
        // this.initialize()
    },
    methods:{
        ...mapActions("classList",["GET_CLASSES","GET_SECTIONS_SELECTION","GET_CLASS_STUDENTS2"]),
        initialize(){
            this.GET_CLASSES_DATA()
            this.GET_STUDENTS_DATA()
        },
        get_sections_subjects_data(){
            let payload = {
                grade_level_id: this.grade_level_id
            }
            this.GET_SECTIONS_SELECTION(payload)
            // this.GET_SUBJECTS_SELECTION(payload)
        },
        GET_CLASSES_DATA(){

            let payload = {}
            if(this.school_year_id !== 'all'){
                payload.school_year_id = this.school_year_id
            }
            if(this.grade_level_id !== 'all' && this.grade_level_id !== ''){
                payload.grade_level_id = this.grade_level_id
            }
            if(this.section_id !== 'all' && this.section_id !== ''){
                payload.section_id = this.section_id
            }

             setTimeout(()=>{this.GET_CLASSES(payload)},600)
        },
        GET_STUDENTS_DATA(){

            let payload = {}
            if(this.school_year_id !== 'all'){
                payload.school_year_id = this.school_year_id
            }
            if(this.section_id !== 'all' && this.section_id !== ''){
                payload.section_id = this.section_id
            }
            if(this.search !== ''){
                payload.search = this.search
            }
            setTimeout(()=>{this.GET_CLASS_STUDENTS2(payload)},600)
            
        },
        action({action, data, index, message}){
            switch(action){
                case 'add':
                
                this.classForm = {show: true, action, data:{}}
                break
                 case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.classForm = { show: true, action, data , index}
                break;
                case 'updateSchedule':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.scheduleForm = { show: true, action, data , index}
                break;
                case 'addStudents':
                this.studentForm = {show: true, action, data: {id: this.$route.params.id}}
                break
                case 'view':
                this.classForm = { show: true, action, data , index}
                break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index,message};
                break
            }
        },
        viewDetails(item)
        {
            this.SET_DATA(item)
            const path = `/admin/class/${item.id}/students`
            this.$router.push(path)
        },
        closeForm(){
            this.classForm.show = false
            this.classForm.data = {}
            this.scheduleForm.show = false
            this.scheduleForm.data = {}
            this.studentForm.show = false
            this.studentForm.data = {}
            setTimeout(()=>{
                this.confirmForm = { show: false, action: '', data:{} }
            }, 5800)
            this.GET_STUDENTS_DATA()
        },
        SET_DATA(item){
            this.SET_LOCALSTORAGE_DATA('class', item)
        },
        removeStudent(item, index){
            let payload = {
                index,
                id: item.student_id,
                school_year_id: item.school_year_id,
                section_id: item.section_id
            }
            this.$store.dispatch("classList/REMOVE_CLASS_STUDENT2", payload)
            this.GET_STUDENTS_DATA()
        },
    }
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