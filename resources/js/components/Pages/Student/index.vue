<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>Students</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                        <v-col cols="12" md="7">
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
                        <!-- <v-col cols="12" md="4">
                            <v-select
                            v-model="gradeLevel"
                            :items="gradeLevels"
                            label="Grade"
                            hide-details
                            outlined
                            dense
                             @change="GET_CLASSES_DATA"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-select
                            v-model="section"
                            :items="sections"
                            label="Section"
                            hide-details
                            outlined
                            dense
                             @change="GET_CLASSES_DATA"
                            >
                            </v-select>
                        </v-col> -->
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
                        <span>Add Student</span>
                    </v-btn>
                </v-col>
                <!-- <v-col cols="12" md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize"
                        color="#CB3737" 
                        outlined
                        elevation="0" 
                        height="39"
                         @click="action({ action: 'add' })"
                        >
                        <v-icon left> mdi-clipboard-list-outline </v-icon>
                        <span>View Students</span>
                    </v-btn>
                </v-col> -->
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="students"
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
        <student-form 
          v-bind="classForm"  
          @close="closeForm"
          @edit="action({ ...classForm, action: 'edit', show: true })"
        >
        </student-form>
         <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import StudentForm from './forms/student-form.vue';
import ConfirmForm from './forms/confirm-form.vue';
export default {
    name:'class',
    components:{
        StudentForm,
        ConfirmForm,
    },
    mixins: [SelectionMixins],
    data(){
        return{
            search:'',
            gradeLevel:'All',
            section:'All',
            dataTable:{
                headers:[
                    {text:'ID Number',value:'learners_number',width:'5%'},
                    {text:'First name',value:'first_name',width:'5%',sortable: false, cellClass:'text-capitalize'},
                    {text:'Last name',value:'last_name',width:'5%',sortable: false, cellClass:'text-capitalize'},
                    {text:'Action', value:"actions",width:'5%',sortable: false, align: 'center'}
                    
                ]
            },
            classForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
        }
    },
    computed:{
        ...mapState("student",["students","message"]),
    },
    watch:{
        search(){
            setTimeout(()=>{
                this.getStudentsData()
            },600)
        },
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
    },
    mounted(){
        this.initialize()
    },
    methods:{
        ...mapActions("student",["GET_STUDENTS"]),
        initialize(){
            this.getStudentsData()
        },
        getStudentsData(){

            let payload = {
                // role: 'student'
            }
            if(this.search.length > 0){
                payload.search = this.search
            }

            this.GET_STUDENTS(payload)
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
        closeForm(){
            this.classForm.show = false
            this.classForm.data = {}
        },
        closeDialog(){
            this.confirmForm = { show: false, action: '', data:{} }
        },
        viewDetails(id)
        {
          const path = `/admin/students/${id}`
          this.$router.push(path)
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