<template>
      <v-card id="main-page" class="pa-5" height="100%">
        <v-btn class="pl-0" plain text @click="goBack()"><v-icon>mdi-chevron-left</v-icon> Back</v-btn>
        <v-card-title class="title">
            <span>Teacher's Information</span>
            <v-spacer></v-spacer>
            <v-btn color="#B71C1C" 
                    width="150"
                    outlined
                    elevation="0" 
                    height="39"
                    @click="action({ action: 'edit'})">
                    <v-icon left>mdi-account-edit</v-icon> 
                    <span class="text-capitalize">Edit Profile</span></v-btn>
        </v-card-title>
        <v-card-text>
           <v-row >
               <v-col cols="12">
                   <v-card-subtitle>ID Number :  {{teacher.instructor_number}}</v-card-subtitle>
                    <v-card-subtitle class="text-capitalize">Name :  {{fullName}}</v-card-subtitle>
               </v-col>
           </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-title> Class Load</v-card-title>
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
                            v-model="school_year_id"
                            :items="sy_selection"
                            item-text="key"
                            item-value="value"
                            label="Select School Year"
                            hide-details
                            outlined
                            dense
                            @change="get_classes_data"
                            >
                            </v-select>
                        </v-col>
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="12" md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize" 
                        color="success"
                        elevation="0" 
                        outlined
                        height="39"
                         @click="action({ action: 'addSubject' })"
                        >
                        <v-icon left> mdi-plus-box-outline </v-icon>
                        <span>Assign Subject</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="classes"
                 hide-default-footer
                >
                <template v-slot:[`item.actions`]="{ item }">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        v-on="on"
                        v-bind="attrs"
                        @click="removeSubject(item.id)"
                        >
                        mdi-delete-forever
                        </v-icon>
                    </template>
                <span>Remove Subject</span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
        <teacher-form 
          v-bind="teacherForm"  
          @close="teacherForm = { ...teacherForm, show: false, data: {}}"
          @edit="action({ ...teacherForm, action: 'edit', show: true })"
        >
        </teacher-form>
         <subject-form 
          v-bind="subjectForm"  
          @close="subjectForm = { ...subjectForm, show: false, data: {}}"
        >
        </subject-form>
        <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import teacherForm from './forms/teacher-form.vue';
import subjectForm from './forms/subject-form.vue';
import confirmForm from './forms/confirm-form.vue';
export default {
    name: 'teacher-details',
    components: {
        teacherForm,
        subjectForm,
        confirmForm,
    },
    // mixins:[LocalStorageMixins],
    data() {
        return {
            dataTable:{
                headers:[
                    {text:'Section',value:'section'},
                    {text:'Subject',value:'subject',cellClass:'text-capitalize'},
                    {text:'Days',value:'days'},
                    {text:'Start time',value:'start_time'},
                    {text:'End time',value:'end_time'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}
                ],
                data:[]
            },
            teacherForm: { show: false, action: '', data: {} },
            pageLimit: 10,
            pageSelection:[10,20,50,100],
            page: 1,
            totalPage:0,
            search:'',
            timer:'',
            isLoading:false,
            showPagination: false,
            teacherForm: { show: false, action: '', data: {} },
            subjectForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
            school_year_id: ''
        }
    },
    computed: {
        ...mapState("teacher",["teacher","classes","message"]),
        ...mapState("classList",["sy_selection"]),
        fullName(){
            return `${this.teacher.first_name} ${this.teacher.last_name}`
        },
    },
    watch:{
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
        sy_selection(to){
            if(to){
                this.school_year_id = to.at(-1)
            }
        }
    },
    mounted(){
        this.init()
    },
    methods: {
      ...mapActions("teacher", ['GET_TEACHER','GET_CLASSES']),
        goBack(){
            this.$router.go(-1)
        },
        init(){
            this.GET_TEACHER(this.$route.params.id)
            this.get_classes_data()
        },
        get_classes_data(){
            let payload = {
                id: this.$route.params.id,
            }
            if(this.school_year_id !== ""){
                payload.school_year_id = this.school_year_id
            }
            this.GET_CLASSES(payload)
        },
        action({action, data, index, message}){

            switch(action){
                case 'add':
                
                this.teacherForm = {show: true, action, data:{}}
                break
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.teacherForm = { show: true, action, data: this.teacher, index: this.teacher.id}
                break;
                case 'view':
                    this.teacherForm = { show: true, action, data , index}
                break
                case 'delete':
                    this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                    this.confirmForm = {show: true, action, data, index, message};
                break
                case 'addSubject':
                    this.subjectForm = {show: true, action, data:{}, index:this.teacher.id}
                break
            }
        },
        removeSubject(id){
            this.$store.dispatch("teacher/REMOVE_SUBJECT", id)
        },
        closeDialog(){
            this.confirmForm = { show: false, action: '', data:{} }
        }

    },
}
</script>

<style lang="scss" scoped>
// @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

</style>
