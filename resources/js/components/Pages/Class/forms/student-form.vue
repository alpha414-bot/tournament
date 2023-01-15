<template>
    <v-dialog
        v-model="dialog"
        max-width="800"
        id="class-form"
        persistent
    >
        <v-card >
             <v-toolbar color="#CB3737" dark flat>
                <v-spacer></v-spacer>  
                <v-toolbar-title>
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Add'}` }} Student
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
                <v-form ref="formData" lazy-validation>
                    <v-container class="px-10">
                        <!-- <div class="my-4 title black--text"> User Details</div> -->
                        <v-row>
                           <v-col cols="12" md="4">
                                <v-text-field
                                v-model="search"
                                placeholder="Search name"
                                prepend-inner-icon="mdi-magnify"
                                hide-details
                                outlined
                                dense
                                >
                                </v-text-field>
                            </v-col>
                          
                        </v-row>
                         <v-row>
                            <v-col cols="12">
                             <v-data-table
                                v-model="assignedStudents"
                                :headers="dataTable.headers"
                                :items="students_selection"
                                :single-select="singleSelect"
                                show-select
                                >
                            </v-data-table>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions class="px-10">
            <v-container class="px-10">
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
                    :disabled="this.assignedStudents.length > 0 ? false : true"
                    @click="handleSubmit"
                >
                    <span class="text-capitalize">{{this.action ==='view' ? 'Edit' : this.action === 'add' ? 'Add' : 'Save'}}</span>
                </v-btn>
            </v-row>
            </v-container>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
import SelectionMixins from '../../../../mixins/selectionMixins'
import LocalStorageMixins from '../../../../mixins/localStorage'
import { mapState,mapActions } from 'vuex'
export default {
    name:'subject-form',
    mixins: [SelectionMixins,LocalStorageMixins],
    props:{
        show: { type: Boolean, default: false, required: true },
        action: { type: String, default: '' },
        data: { type: Object, default: new Object() },
        index: { default:''}
    },
    data(){
        return{
            menu: false,
            dialog: this.show,
            dataTable:{
                headers:[
                    {text:'Learners_number',value:'learners_number'},
                    {text:'First Name',value:'first_name',},
                    {text:'Last Name',value:'last_name'},
                    
                ],
                data:[]
            },
            assignedStudents:[],
            singleSelect:false,
            subjectSelection:[],
            formData:{
                user_id: '',
                section_id: '',
                school_year_id: '',
                grade_level_id:''
            },
            rules:{
                    sectionRules:[
                        value =>  !! value || "Section is required",
                    ],
                    syRules:[  
                            value =>  !! value || "School Year is required",    
                    ],
                },
            search:''
        }
    },
    computed:{
         ...mapState("classList", [
            "sy_selection",
            "grade_level_selection",
            "sections_selection",
            "subjects_selection",
            "students_selection",
            ]),
    },
    watch:{
        show(value) {
            this.dialog = value
            if(value){ 
                this.assignedStudents = [];
                this.get_students_data();
            }
        },
        dialog(value){
            if (!value) {
                this.clearForm();
                this.$emit('close');
            }
        },
        search(to){
            setTimeout(() => {
                this.get_students_data()
            },600)
            
        },
        // data:{
        //         handler(data){
        //         if(Object.keys(data).length > 0){
        //             let {first_name,last_name,email,mobile,instructor_number,birth_date,address,
        //                 role,contact_name,contact_number,contact_relationship} = data
                   
        //             this.formData = {
        //                 first_name,
        //                 last_name,
        //                 email,
        //                 instructor_number,
        //                 mobile,
        //                 birth_date,
        //                 address,
        //                 role,
        //                 contact_name,
        //                 contact_number: contact_number !== '' ? contact_number.replace('+639',''): '',
        //                 contact_relationship,
        //                 mobile: mobile.replace('+639',''),
        //                 changePassword: false,
        //             }
        //         }
        //         },
        //         deep: true,
        //         immediate: true,
        //     },
        assignedStudents(to){
            }
    },
    methods:{
        ...mapActions("classList",["GET_SECTIONS_SELECTION","GET_SUBJECTS_SELECTION","GET_STUDENTS_SELECTION"]),
        get_students_data(){
            let payload = {
                class_id: this.$route.params.id
            }

            if( this.search !== ''){
                payload.search = this.search
            }
            this.GET_STUDENTS_SELECTION(payload)
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate();

            if(formStatus){
                if(this.action == 'addStudents'){
                    let payload = {
                        class_id: this.$route.params.id,
                        students: this.assignedStudents
                    }
                    this.$store.dispatch("classList/ADD_CLASS_STUDENTS", payload)
                 }
                if(this.action == 'edit'){

                    // if(data.password === ""){
                    //      delete data.password
                    //    }
                    let payload = {
                        id: this.index,
                        user: data
                    }
                    this.$store.dispatch("teacher/UPDATE_TEACHER", payload)
                 }
                this.dialog = false 
            }
        },
        clearForm(){
            this.$refs.formData.resetValidation();

            this.formData = {
                first_name: '',
                last_name: '',
                email: '',
                birth_date:'',
                instructor_number: '',
                mobile:'',
                address: '',
                contact_name: '',
                contact_number: '',
                contact_relationship: '',
                changePassword: false
            }
        },
        
    }
}
</script>

<style lang="sass" scoped>

</style>