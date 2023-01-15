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
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Add'}` }} Class
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
                           <v-col  cols="12" md="4">
                                <span class="my-4 text-subtitle-1"> Grade Level </span>
                                <v-select
                                v-model="formData.grade_level_id"
                                :items="grade_level_selection"
                                item-text="key"
                                item-value="value"
                                label="Select grade level"
                                outlined
                                dense
                                
                                flat
                                @change="get_sections_subjects_data"
                                :rules="[value => !! value || 'Grade Level is required']"
                                ></v-select>
                            </v-col>
                            <v-col  cols="12" md="4">
                                <span class="my-4 text-subtitle-1"> Section </span>
                                <v-select
                                v-model="formData.section_id"
                                :items="sections_selection"
                                item-text="key"
                                item-value="value"
                                label="Select section"
                                outlined
                                dense
                                
                                flat
                                :rules="[value => !! value || 'Section is required']"
                                ></v-select>
                            </v-col>
                            <v-col  cols="12" md="4">
                                <span class="my-4 text-subtitle-1"> School Year</span>
                                <v-select
                                v-model="formData.school_year_id"
                                :items="sy_selection"
                                item-text="key"
                                item-value="value"
                                label="Select school year"
                                outlined
                                dense
                                
                                flat
                                :rules="[value => !! value || 'School Year is required']"
                                ></v-select>
                            </v-col>
                        </v-row>
                         <v-row>
                            <v-col cols="12">
                             <v-data-table
                                v-model="assignedSubjects"
                                :headers="dataTable.headers"
                                :items="subjects_selection"
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
                    :disabled="this.assignedSubjects.length > 0 ? false : true"
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
                    {text:'Subject Code',value:'code'},
                    {text:'Title',value:'title',},
                    {text:'Grade Level',value:'grade_level'},
                    
                ],
                data:[]
            },
            assignedSubjects:[],
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
        }
    },
    computed:{
         ...mapState("classList",["sy_selection","grade_level_selection","sections_selection","subjects_selection"]),
    },
    watch:{
        show(value) {
            this.dialog = value
            if(value){ 
                this.assignedSubjects = [];
                // this.GET_LOCALSTORAGE_DATA()
            }
        },
        dialog(value){
            if (!value) {
                this.clearForm();
                this.$emit('close');
            }
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
        // assignedSubjects(to){
        //         console.log(to)
        //     }
    },
    methods:{
        ...mapActions("classList",["GET_SECTIONS_SELECTION","GET_SUBJECTS_SELECTION"]),
         get_sections_subjects_data(){
            let payload = {
                grade_level_id: this.formData.grade_level_id
            }
            this.GET_SECTIONS_SELECTION(payload)
            this.GET_SUBJECTS_SELECTION(payload)
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate();

            if(formStatus){
                if(this.action == 'add'){
                    let payload = {
                        grade_level_id: this.formData.grade_level_id,
                        section_id:     this.formData.section_id,
                        school_year_id: this.formData.school_year_id,
                        subjects:       this.assignedSubjects
                    }
                    // console.log(payload)
                    this.$store.dispatch("classList/ADD_CLASS", payload)
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