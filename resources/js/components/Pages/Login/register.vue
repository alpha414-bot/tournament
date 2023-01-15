<template>
    <v-row>
        <v-dialog v-model="dialog" persistent max-width="800px">
            <!-- <template v-slot:activator="{ on, attrs}">

            </template> -->
            <v-card>
                <v-card-title>
                    <span class="text-h5">Register</span>
                </v-card-title>
                <v-card-text>
                    <v-form ref="formData">
                        <v-container>
                            <v-alert v-for="(item, key) in alertMessage" v-bind:data="item" v-bind:key="key" v-model="alert" border="right" colored-border close-text="Close Alert" dismissible type="error" elevation="2" >
                                {{ item[0] }}
                            </v-alert>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field label="First Name (*)" v-model="formData.first_name" :rules="rules.firstNameRule"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field label="Last Name (*)" v-model="formData.last_name" :rules="rules.lastNameRule"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field label="Email Address (*)" v-model="formData.email" :rules="rules.emailRule"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field label="Mobile Number" v-model="formData.user_number" :rules="rules.mobileNumberRule" @keypress="validate.numbersOnly" type="tel" prefix="+639" maxlength="9" counter="9"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="4" sm="6">
                                    <v-text-field label="Contact Name" v-model="formData.contact_name" :rules="rules.contactNameRUle"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4" sm="6">
                                    <v-text-field label="Contact Number" v-model="formData.contact_number" :rules="rules.contactNumberRule" prefix="+639" type="tel" @keypress="validate.numbersOnly" maxlength="9" counter="9"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4" sm="6">
                                    <v-select label="Relationship" v-model="formData.contact_relationship" :items="relationships" outlined></v-select>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-text-field label="Password (*)" v-model="formData.password" :rules="rules.passwordRule" type="password"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="12">
                                    <v-textarea label="Address" v-model="formData.address" outlined :rules="rules.addressRule"></v-textarea>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" text @click="dialog=false">Cancel</v-btn>
                    <v-btn color="red darken-1" text="" @click="handleSubmit">Register</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
    import {mapState} from 'vuex'
    export default {
        class: 'class-form',
        props: {
            show:{type: Boolean, default: false, required: true}
        },
        computed: {
            ...mapState("auth",["messageBag"])
        },
        data(){
            return {
                dialog: this.show,
                alert: false,
                alertMessage: {},
                formData: {
                    first_name: '',
                    last_name: '',
                    email: '',
                    user_number: '',
                    contact_name: '',
                    contact_number: '',
                    contact_relationship: '',
                    password: '',
                    address: ''
                },
                relationships: ['Parent', 'Guardian'],
                rules: {
                    firstNameRule: [
                        value => !! value || "First Name is required",
                        value => this.nameRegex.test(value)
                    ],
                    lastNameRule: [
                        value => !! value || "Last Name is required",
                        value => this.nameRegex.test(value)
                    ],
                    emailRule: [
                        value=> !! value || "Email Address is required",
                        value => this.emailRegex.test(value) || 'E-mail must be valid',
                    ],
                    mobileNumberRule: [
                        // value => !! value || "Mobile number is required",
                        value => (!isNaN(value) || "Mobile number must be number only"),
                        value =>  value.length === 9 || "Mobile number is invalid"
                    ],
                    passwordRule: [
                        value => !! value || "Password is required",
                        value => this.passwordRegex.test(value) || "Password should be 8 characters long, containing uppercase, number and special character.",
                    ]
                },
                nameRegex: /^(?!.*\s\s)[A-Za-z\.\'\-]*[\w\d\'\s.-]+[\w\d\.\'\-]$/,
                emailRegex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                passwordRegex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/,
                passwordSpecialCharacter: /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/,
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
        watch: {
            show(value){
                this.dialog=value
            },
            dialog(value){
                if(!value){
                    this.clearForm();
                    this.$emit('close');
                }
            },
            messageBag(message){
                // console.log(message)
                if(message!=''){
                    this.alert = true;
                    this.alertMessage = message.message
                }
            }
        },
        methods: {
            handleSubmit(){
                let formStatus = this.$refs.formData.validate();
                if(formStatus){
                    this.$store.dispatch("auth/student_register", this.formData);
                    // this.dialog = false
                }
            },
            clearForm(){
                this.$refs.formData.resetValidation();
                this.formData = {
                    first_name: '',
                    last_name: '',
                    email: '',
                    user_number: '',
                    contact_name: '',
                    contact_number: '',
                    contact_relationship: '',
                    password: '',
                    address: ''
                }
            }
        }
    }
</script>

<style lang="sass" scoped></style>