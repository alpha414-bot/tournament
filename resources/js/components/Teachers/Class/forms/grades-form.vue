<template>
    <v-dialog
        v-model="dialog"
        max-width="600"
        id="class-form"
        persistent
    >
        <v-card >
             <v-toolbar color="#CB3737" dark flat>
                <v-spacer></v-spacer>  
                <v-toolbar-title>
                    Grades
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
                        <div class="my-4 title black--text"> Student Name</div>
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                            <v-text-field
                            v-model="full_name"
                            label="Enter first name"
                            outlined
                            dense
                            
                            flat
                            :readonly="true"
                            ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                          <v-col  cols="12">
                                <span class="my-4 text-subtitle-1"> First Quarter </span>
                                <v-text-field
                                v-model="formData.first"
                                maxlength="5"
                                counter="5"
                                label="Enter grade"
                                outlined
                                dense
                                
                                flat
                                @keypress="validate.numbersOnly"
                                ></v-text-field>
                            </v-col>
                            <v-col  cols="12" >
                                <span class="my-4 text-subtitle-1"> Second Quarter </span>
                                <v-text-field
                                v-model="formData.second"
                                type="text"
                                maxlength="5"
                                counter="5"
                                label="Enter grade"
                                outlined
                                dense
                                
                                flat
                                @keypress="validate.numbersOnly"
                                ></v-text-field>
                            </v-col>
                            <v-col  cols="12">
                                <span class="my-4 text-subtitle-1"> Third Quarter </span>
                                <v-text-field
                                v-model="formData.third"
                                type="text"
                                maxlength="5"
                                counter="5"
                                label="Enter grade"
                                outlined
                                dense
                                
                                flat
                                @keypress="validate.numbersOnly"
                                ></v-text-field>
                            </v-col>
                            <v-col  cols="12">
                                <span class="my-4 text-subtitle-1"> Fourth Quarter </span>
                                <v-text-field
                                v-model="formData.fourth"
                                type="text"
                                maxlength="5"
                                counter="5"
                                label="Enter grade"
                                outlined
                                dense
                                
                                flat
                                @keypress="validate.numbersOnly"
                                ></v-text-field>
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
export default {
    name:'class-form',
    mixins: [SelectionMixins],
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
            show_password: false,
            show_confirm_password: false,
            disableConfirmPassword: false,
            full_name: '',
            formData:{
               first: null,
               second: null,
               third:  null,
               fourth: null,
            },
            rules:{
                    numberRules:[
                        value => this.numberRegex.test(value) || "Grade must be number only",
                        value =>  value.length <=5 || "Grade is invalid"
                    ],
            },
            validate:{
                    numbersOnly($event, withPoint) {
                        let keyCode = $event.keyCode ? $event.keyCode : $event.which;
                        if ((keyCode < 46 || keyCode > 57 )) { $event.preventDefault(); }
                    },
            },
             numberRegex: /^[1-9]\d*(\.\d+)?$/,
        }
    },
    watch:{
        show(value) {
            this.dialog = value
            // if(value){ 
            //     this.initialize()
            // }
        },
        dialog(value){
            if (!value) {
                this.clearForm();
                this.$emit('close');
            }
        },
        data:{
                handler(data){
                if(Object.keys(data).length > 0){
                    let {first, second, third, fourth, first_name, last_name} = data
                    this.full_name = first_name + ' ' + last_name
                    this.formData = {first, second, third, fourth}
                    
                }
                },
                deep: true,
                immediate: true,
            },
            // formData:{
            //     handler({password}){
                    
            //         let result = this.passwordRegex.test(password) ? false : true
            //         this.disableConfirmPassword = result
            //     },
            //     deep: true
            // },
    },
    methods:{
        handleSubmit(){
            let formStatus = this.$refs.formData.validate();
            const {first, second, third, fourth} = this.formData;
            if(formStatus){

                if(this.action == 'setGrades'){
                    
                    let payload = {
                        id: this.data.id,
                    }
                    if(first !== '' && first !== 0){
                        payload.first = first
                    }
                    if(second !== '' && second !== 0){
                        payload.second = second
                    }
                    if(third !== '' && third !== 0){
                        payload.third = third
                    }
                    if(fourth !== '' && fourth !== 0){
                        payload.fourth = fourth
                    }
                    this.$store.dispatch("classList/UPDATE_STUDENT_GRADE", payload)
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
        
    }
}
</script>

<style lang="sass" scoped>

</style>