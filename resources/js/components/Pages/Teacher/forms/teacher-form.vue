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
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Add New'}` }} Teacher
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
                        <div class="my-4 title black--text"> User Details</div>
                        <v-row>
                            <v-col
                                cols="12"
                                md="6"
                            >
                            <span class="my-4 text-subtitle-1"> First name </span>
                            <v-text-field
                            v-model="formData.first_name"
                            :rules="rules.firstNameRules"
                            label="Enter first name"
                            outlined
                            
                            flat
                            @keypress="validate.lettersOnly"
                            ></v-text-field>
                            </v-col>
                             <v-col
                                cols="12"
                                md="6"
                            >
                            <span class="my-4 text-subtitle-1"> Last name </span>
                            <v-text-field
                            v-model="formData.last_name"
                            :rules="rules.lastNameRules"
                            label="Enter last name"
                            outlined
                            
                            flat
                            @keypress="validate.lettersOnly"
                            ></v-text-field>
                            </v-col>
                           
                        </v-row>
                        <v-row>
                             <v-col cols="12" md="6">
                            <span class="my-4 text-subtitle-1"> Email </span>
                            <v-text-field
                            v-model="formData.email"
                            :rules="rules.emailRules"
                            label="Enter email address"
                            outlined
                            
                            flat
                            :readonly="action === 'view' && true"
                            value=""
                            autocomplete="off"
                            ></v-text-field>
                        </v-col>
                        <!-- <v-col  cols="12" md="6">
                            <span class="my-4 text-subtitle-1"> Mobile Number </span>
                            <v-text-field
                            v-model="formData.mobile"
                            :rules="rules.mobileNumberRules"
                            type="tel"
                            prefix="+639"
                            maxlength="9"
                            counter="9"
                            label="Enter mobile number"
                            outlined
                            
                            flat
                            @keypress="validate.numbersOnly"
                            :readonly="action === 'view' && true"
                            ></v-text-field>
                        </v-col> -->
                          <v-col  cols="12" md="6">
                                <span class="my-4 text-subtitle-1"> Mobile Number </span>
                                <v-text-field
                                v-model="formData.mobile"
                                :rules="rules.mobileNumberRules"
                                type="tel"
                                prefix="+639"
                                maxlength="9"
                                counter="9"
                                label="Enter mobile number"
                                outlined
                                
                                flat
                                @keypress="validate.numbersOnly"
                                :readonly="action === 'view' && true"
                                ></v-text-field>
                            </v-col>
                        
                        </v-row>
                        <v-row>
                           <v-col cols="12" md="6">
                                <v-menu
                                ref="menu"
                                v-model="menu"
                                max-width="290"
                                :close-on-content-click="false"
                                >
                                    <template v-slot:activator="{ on }">
                                        <span class="my-4 text-subtitle-1"> Birthday </span>
                                        <v-text-field 
                                            label="Select birth date"
                                            prepend-inner-icon="mdi-calendar"
                                            :value="formData.birth_date"
                                            :rules="rules.birthRules"
                                            v-on="on" 
                                            readonly
                                            outlined
                                             
                                            flat></v-text-field>
                                    </template>
                                    <v-date-picker 
                                        v-model="formData.birth_date"
                                        max="2010-12-31"
                                        type="date" 
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="menu = false"> Cancel</v-btn>
                                    <v-btn text color="primary" @click=" menu = !menu;"> APPLY </v-btn>
                                    </v-date-picker>
                            </v-menu> 
                           
                           </v-col>
                            <v-col cols="12" md="6">
                            <span class="my-4 text-subtitle-1"> ID Number</span>
                              <v-text-field
                                v-model="formData.instructor_number"
                                :rules="rules.idRules"
                                label="Enter teacher's id"
                                outlined
                                
                                flat
                                :readonly="action === 'view' && true"
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                            >
                            <span class="my-4 text-subtitle-1"> Address</span>
                            <v-text-field
                            v-model="formData.address"
                            :rules="rules.addressRules"
                            label="Enter address"
                            outlined
                            
                            flat
                            ></v-text-field>  
                            </v-col>
                        </v-row>

                        <div class="my-4 title black--text"> Contact Details</div>
                        <v-row>
                            <v-col cols="12" md="4">
                            <span class="my-4 text-subtitle-1"> Name </span>
                            <v-text-field
                            v-model="formData.contact_name"
                            label="Enter contact's name"
                            outlined
                            
                            flat
                            :readonly="action === 'view' && true"
                            value=""
                            autocomplete="off"
                            ></v-text-field>
                        </v-col>
                        <v-col  cols="12" md="4">
                            <span class="my-4 text-subtitle-1"> Mobile Number </span>
                            <v-text-field
                            v-model="formData.contact_number"
                            type="tel"
                            prefix="+639"
                            maxlength="9"
                            counter="9"
                            label="Enter mobile number"
                            outlined
                            
                            flat
                            @keypress="validate.numbersOnly"
                            :readonly="action === 'view' && true"
                            ></v-text-field>
                        </v-col>
                        <v-col  cols="12" md="4">
                            <span class="my-4 text-subtitle-1"> Relationship </span>
                            <v-select
                            v-model="formData.contact_relationship"
                            :items="relationships"
                            label="Select relation"
                            outlined
                            
                            flat
                            ></v-select>
                        </v-col>
                        </v-row>
                        <v-row v-if="action === 'edit'">
                            <v-col>
                                <div class="my-4 title black--text">Password</div>
                                <template >
                                    <v-checkbox
                                        v-model="formData.changePassword"
                                        label="Reset Password"
                                        ></v-checkbox>
                                </template>
                            </v-col>
                        </v-row>
                        <!-- <template v-if="action !== 'view'"> -->
                        <!-- <v-divider></v-divider>
                        
                        
                        <v-row v-if="action === 'add'? true : changePassword === true ">
                            <v-col
                                cols="12"
                                sm="6"
                            >
                                <span class="my-4 text-subtitle-1"> Password </span>
                                <v-text-field
                                    v-model="formData.password"
                                    :rules="rules.passwordRules"
                                    label="Enter password"
                                    outlined
                                    
                                    flat
                                    :type="show_password ? 'text' : 'password'"
                                    :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                                    @click:append="show_password = !show_password"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <div>
                                <span class="my-4 text-subtitle-1"> Confirm Password </span>
                                <v-text-field
                                    v-model="formData.confirm_password"
                                    :rules="rules.confirmPasswordRules"
                                    label="Enter password"
                                    outlined
                                    
                                    flat
                                    :type="show_confirm_password ? 'text' : 'password'"
                                    :append-icon="show_confirm_password ? 'mdi-eye' : 'mdi-eye-off'"
                                    @click:append="show_confirm_password = !show_confirm_password"
                                    :disabled="disableConfirmPassword"
                                ></v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                        </template> -->
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
            formData:{
                first_name: '',
                last_name: '',
                email: '',
                instructor_number: '',
                // password: '',
                // confirm_password:'',
                mobile:'',
                address: '',
                contact_name: '',
                contact_number: '',
                contact_relationship: '',
                changePassword:false,
                
            },
            rules:{
                    firstNameRules:[
                        value => !! value || "First Name is required",
                        value => this.nameRegex.test(value) 
                    ],
                    lastNameRules:[
                        value => !! value || "Last Name is required",
                        value => this.nameRegex.test(value)
                    ],
                    emailRules: [
                        value=> !! value || "Email Address is required",
                        value => this.emailRegex.test(value) || 'E-mail must be valid',
                    ],
                    roleRules:[
                        value => !! value || "Role is required"
                    ],
                    mobileNumberRules:[
                        value => !! value || "Mobile number is required",
                        value => !! value && (!isNaN(value) || "Mobile number must be number only"),
                        value =>  value.length === 9 || "Mobile number is invalid"
                    ],
                    passwordRules:[
                        value =>  !! value || "Password is required",
                        value => this.passwordRegex.test(value) || "Password is invalid.",
                        // value =>  /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(value),
                    ],
                    confirmPasswordRules:[
                        value =>  !! value || "Confirm Password is required",
                        value => value === this.formData.password || 'Passwords must match'
                    ],
                    birthRules:[
                        value =>  !! value || "Birthday is required",
                    ],
                    addressRules:[  
                         value =>  !! value || "Address is required",    
                    ],
                    idRules:[
                        value =>  !! value || "Number is required",
                    ]

                },
                // nameRegex: /^(\w+\s?)*\S{1,}$/,
                nameRegex: /^(?!.*\s\s)[A-Za-z\.\'\-]*[\w\d\'\s.-]+[\w\d\.\'\-]$/,
                emailRegex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/, // Email Validator
                // phoneNumberRegex: /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/,
                passwordRegex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/,
                passwordSpecialCharacter: /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/,
                lowercaseCharacter: "(?=.*[a-z])",
                validate:{
                    numbersOnly($event, withPoint) {
                        let keyCode = $event.keyCode ? $event.keyCode : $event.which;
                        if ((keyCode < 48 || keyCode > 57) && !((keyCode == 190 || keyCode == 46) && withPoint)) { $event.preventDefault(); }
                    },
                    lettersOnly($event){
                        let keyCode = $event.keyCode ? $event.keyCode : $event.which;
                        if ((keyCode < 97 || keyCode > 122) && (keyCode < 65 || keyCode > 90) && keyCode != 39 && keyCode != 46 && keyCode != 32 && keyCode != 45) { $event.preventDefault(); }
                    },
                }
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
                    let {first_name,last_name,email,mobile,instructor_number,birth_date,address,
                        role,contact_name,contact_number,contact_relationship} = data
                   
                    this.formData = {
                        first_name,
                        last_name,
                        email,
                        instructor_number,
                        mobile,
                        birth_date,
                        address,
                        role,
                        contact_name,
                        contact_number: contact_number !== '' ? contact_number.replace('+639',''): '',
                        contact_relationship,
                        mobile: mobile.replace('+639',''),
                        changePassword: false,
                    }
                    
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

            if(formStatus){

                const { first_name, last_name, email, instructor_number, password, mobile,
                        birth_date, address, contact_name, contact_number, contact_relationship } = this.formData
                const data = { first_name, last_name, email, instructor_number, password,  address, 
                               birth_date, contact_name,  contact_relationship ,
                               mobile: "+639" + mobile,
                               contact_number: "+639" + contact_number,
                               role: 'teacher',
                               admission_type: 'none',
                               changePassword: this.formData.changePassword
                            }

                if(this.action == 'add'){
                            this.$store.dispatch("teacher/CREATE_TEACHER", data)
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