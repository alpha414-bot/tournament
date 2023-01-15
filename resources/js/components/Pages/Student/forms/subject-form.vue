<template>
    <v-dialog
        v-model="dialog"
        max-width="1000"
        id="class-form"
        persistent
    >
        <v-card >
             <v-toolbar color="#CB3737" dark flat>
                <v-spacer></v-spacer>  
                <v-toolbar-title>
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Assign'}` }} Subjects
                </v-toolbar-title>
                <v-spacer></v-spacer>
                 <v-tooltip  bottom>
                <template v-slot:activator="{ on }">
                    <v-icon align="left" v-on="on" @click="dialog = false">mdi-close</v-icon>
                </template>
                <span>Close</span>
                </v-tooltip>
            </v-toolbar>
            <v-card-text class="px-10 py-7">
                <v-form ref="formData">
                        <div class="my-4 title black--text">List of Subjects</div>
                        <v-row>
                            <v-col
                                cols="12"
                            >
                             <v-data-table
                                v-model="assignedSubjects"
                                :headers="dataTable.headers"
                                :items="subjects_selection"
                                :single-select="singleSelect"
                                show-select
                                >
                                <template v-slot:[`item.actions`]="{ item }">
                                <!-- <v-tooltip bottom>
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
                                </v-tooltip> -->
                                </template>
                            </v-data-table>
                           
                            </v-col>
                        </v-row>
                </v-form>
            
            </v-card-text>
            <v-card-actions class="px-10">
                <v-row class="px-2 pb-5">
                <v-spacer></v-spacer>
                <v-btn v-show="action !== 'view'"
                    class="pa-5"
                    color="#A4A6B3"
                    width="150"
                    depressed
                    dark
                    @click="dialog = false"
                >
                    <span class="text-capitalize">Cancel</span>
                </v-btn>
                <v-btn
                    class="pa-5 ml-3"
                    color="#B71C1C"
                    width="150"
                    depressed
                    dark
                    :disabled="this.assignedSubjects.length > 0 ? false : true"
                    @click="handleSubmit"
                >
                    <span class="text-capitalize">{{this.action ==='view' ? 'Edit' : this.action === 'add' ? 'Add' : 'Save'}}</span>
                </v-btn>
            </v-row>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import SelectionMixins from '../../../../mixins/selectionMixins'
import LocalStorageMixins from '../../../../mixins/localStorage'
import { mapState,mapActions } from 'vuex'
export default {
    name:'class-form',
    mixins: [SelectionMixins,LocalStorageMixins],
    props:{
        show: { type: Boolean, default: false, required: true },
        action: { type: String, default: '' },
        data: { type: Object, default: new Object() },
        index: { default:''},
    },
    data(){
        return{
            menu: false,
            dialog: this.show,
            show_password: false,
            dataTable:{
                headers:[
                    {text:'Subject Code',value:'code'},
                    {text:'Title',value:'title',},
                    {text:'Grade Level',value:'grade_level'},
                    {text:'Actions', value:"actions",sortable: false, align:'center'}
                ],
                data:[]
            },
            assignedSubjects:[],
            singleSelect:false,
            subjectSelection:[]
        }
    },
    computed:{
        ...mapState("student",["subjects_selection"])
    },
    watch:{
        show(value) {
            this.dialog = value
            if(value){ 
                this.assignedSubjects = [];
                this.GET_LOCALSTORAGE_DATA()
            }
        },
        dialog(value){
            if (!value) {
                // this.clearForm();
                this.$emit('close');
            }
        },
        // data:{
        //         handler(data){
        //         if(Object.keys(data).length > 0){
                    
        //         }
        //         },
        //         deep: true,
        //         immediate: true,
        //     },
        assignedSubjects(to){
            // console.log(to)
        }
    },
    methods:{
        ...mapActions("student",["GET_SUBJECTS_SELECTION"]),
        handleSubmit(){
            if(this.assignedSubjects.length > 0){

                if(this.action == 'add'){
                    let payload = {
                        'enrollment_id': this.index,
                        'subjects': this.assignedSubjects
                    }
                    this.$store.dispatch("student/ASSIGN_SUBJECTS", payload)
                 }
                if(this.action == 'edit'){

                    // if(data.password === ""){
                    //      delete data.password
                    //    }
                    let payload = {
                        id: this.index,
                        user: data
                    }
                    this.$store.dispatch("student/UPDATE_STUDENT", payload)
                 }
                this.dialog = false 
            }
        },
        // clearForm(){
        //     this.$refs.formData.resetValidation()
        //     this.formData = {
        
        //     }
        // },
        GET_LOCALSTORAGE_DATA(){
            let data = localStorage.getItem('student') ? JSON.parse(localStorage.getItem('student')) : ''
            let payload = {
                enrollment_id: this.index,
                grade_level_id: `${data.grade_level.id}` 
            }
            this.GET_SUBJECTS_SELECTION(payload)
        },
        
    }
}
</script>

<style lang="sass" scoped>

</style>