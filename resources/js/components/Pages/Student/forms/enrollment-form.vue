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
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'New'}` }} Enrollment
                </v-toolbar-title>
                <v-spacer></v-spacer>
                 <v-tooltip  bottom>
                <template v-slot:activator="{ on }">
                    <v-icon align="left" v-on="on" @click="dialog = false">mdi-close</v-icon>
                </template>
                <span>Close</span>
                </v-tooltip>
            </v-toolbar>
                <v-container class="px-10">
                    <v-card-title> Learners number: {{ data.learners_number }}</v-card-title>
                    <v-card-title> Name: {{ data.first_name }} {{data.last_name}}</v-card-title>
                </v-container>
            <v-card-text>
                <v-form ref="formData" lazy-validation>
                    <v-container class="px-10">
                        <div class="title black--text"> User Details</div>
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
                                @change="get_sections_data"
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
                                label="Select grade level"
                                outlined
                                dense
                                
                                flat
                                :rules="[value => !! value || 'Grade Level is required']"
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
                                :rules="[value => !! value || 'Grade Level is required']"
                                ></v-select>
                            </v-col>
                        </v-row>
                    </v-container>
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
            },
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
        ...mapState("classList",["sy_selection","grade_level_selection","sections_selection"]),
        student(){
            return `${this.data.learners_number} - ${this.data.first_name} ${this.data.last_name}` 
        }
    },
    watch:{
        show(value) {
            this.dialog = value
            if(value){ 
                this.GET_LOCALSTORAGE_DATA()
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
        //             let {first_name,last_name,email,mobile,learners_number,birth_date,address,
        //                 role,contact_name,contact_number,contact_relationship, admission_type,
        //                 grade_level} = data
        //         }
        //         },
        //         deep: true,
        //         immediate: true,
        //     },
          
    },
    methods:{
        ...mapActions("classList",["GET_SECTIONS_SELECTION"]),
        get_sections_data(){
            let payload = {
                grade_level_id: this.formData.grade_level_id
            }
            this.GET_SECTIONS_SELECTION(payload)
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate();

            if(formStatus){
                    if(this.action == 'add'){
                        let payload = {
                            user_id: this.data.id,
                            section_id: this.formData.section_id,
                            grade_level_id: this.formData.grade_level_id,
                            school_year_id: this.formData.school_year_id,
                            
                        }
                        this.$store.dispatch("student/ENROLL_STUDENT", payload)
                    }
                    if(this.action == 'edit'){
                        let payload = {
                            id: this.index,
                            user: data
                        }
                        // this.$store.dispatch("student/UPDATE_STUDENT", payload)
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
                learners_number: '',
                mobile:'',
                address: '',
                contact_name: '',
                contact_number: '',
                contact_relationship: '',
                admission_type: '',
                grade_level:'',
                changePassword: false
            }
        },
        GET_LOCALSTORAGE_DATA(){
            let data = localStorage.getItem('student') ? JSON.parse(localStorage.getItem('student')) : ''
            let payload = {
                enrollment_id: this.index,
                grade_level_id: `${data.grade_level.id}` 
            }
        },
        
    }
}
</script>

<style lang="sass" scoped>

</style>