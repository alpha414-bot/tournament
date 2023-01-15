<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>Subjects Management</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                        <v-col cols="12" md="4">
                            <v-text-field
                            v-model="search"
                            placeholder="Search subject"
                            prepend-inner-icon="mdi-magnify"
                            hide-details
                            outlined
                            dense
                            >
                            </v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-select
                            v-model="gradeLevel"
                            :items="gradeLevels"
                            label="Grade"
                            hide-details
                            outlined
                            dense
                            >
                            </v-select>
                        </v-col>
                        <!-- <v-col cols="12" md="4">
                            <v-select
                            :items="sections"
                            label="Section"
                            hide-details
                            outlined
                            dense
                            >
                            </v-select>
                        </v-col> -->
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
                         @click="action({ action: 'add' })"
                        >
                        <v-icon left> mdi-plus-box-outline </v-icon>
                        <span>Create Subject</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="subjects"
                >
                <template v-slot:item.actions="{ item , index }">
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        class=""
                        v-on="on"
                        v-bind="attrs"
                        @click="action({ action: 'edit', data: item, index })"
                        >
                        mdi-playlist-edit
                        </v-icon>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
        <subject-form 
          v-bind="subjectForm"  
          @close="closeDialog"
          @edit="action({ ...subjectForm, action: 'edit', show: true })"
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
import {mapState, mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import subjectForm from './forms/subject-form.vue';
import confirmForm from './forms/confirm-form.vue';
export default {
    name:'class',
    components:{
        subjectForm,
        confirmForm
    },
    mixins: [SelectionMixins],
    data(){
        return{
            search:'',
            dataTable:{
                headers:[
                    {text:'Subject Code',value:'code',width:'5%'},
                    {text:'Subject Title',value:'title',width:'5%'},
                    {text:'Units',width:'5%',value:'units',sortable: false},
                    {text:'Grade',width:'5%',value:'grade_level.grade_level',sortable: false},
                    {text:'Actions',value:'actions',width:'2%',sortable: false},
                    
                ]
            },
            confirmForm: { show: false, action: '', data:{} },
            subjectForm: { show: false, action: '', data: {} },
            gradeLevel:'all',
            gradeLevels: [
                {text:'All',value:'all'},
                {text:'Grade 7',value:'7'},
                {text:'Grade 8',value:'8'},
                {text:'Grade 9',value:'9'},
                {text:'Grade 10',value:'10'},
                ]
        }
    },
    computed:{
        ...mapState("subject",["subjects","message"]),
    },
    watch:{
        search(data){
            clearTimeout(this.timer)
            this.timer = setTimeout(()=>{
                this.page = 1
                this.GET_SUBJECTS_DATA()
            }, 600);
        },
        gradeLevel(v){
            this.GET_SUBJECTS_DATA()
        },
         message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
    },
    mounted(){
        this.GET_SUBJECTS_DATA()
    },
    methods:{
        ...mapActions("subject",["GET_SUBJECTS"]),
           action({action, data, index, message}){

            switch(action){
                case 'add':
                    this.subjectForm = {show: true, action, data:{}}
                    break
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} 
                    this.subjectForm = { show: true, action, data , index}
                break;
                    case 'view':
                    this.subjectForm = { show: true, action, data , index}
                    break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index,message};
                break
            }
        },
        GET_SUBJECTS_DATA(){
            let payload = {}
            if(this.gradeLevel != 'all'){
               payload.grade_level = this.gradeLevel
            }
            if(this.search !== ''){
                payload.search = this.search
            }
            this.GET_SUBJECTS(payload)
        },
        closeDialog(){
            this.subjectForm = { ...subjectForm, show: false, data: {}}
            setTimeout(()=>{this.confirmForm = { show: false, action: '', data:{} }},800)
            this.GET_SUBJECTS_DATA()
        }
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