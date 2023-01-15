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
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Add New'}` }} Subject
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
                <v-form ref="formData" >
                    <v-container class="px-10">
                        <!-- <div class="my-4 title black--text"> </div> -->
                        <v-row>
                            <v-col>
                            <span class="my-4 text-subtitle-1"> Grade Level</span>
                                <v-select
                                    v-model="formData.grade_level"
                                    :items="gradeSelection"
                                    :rules="reqrules"
                                    outlined
                                    dense
                                    >
                                </v-select>
                            </v-col>
                           <v-col>
                            <span class="my-4 text-subtitle-1"> Units</span>
                                <v-select
                                    v-model="formData.units"
                                    :items="units"
                                    :rules="reqrules"
                                    outlined
                                    dense
                                    >
                                </v-select>
                            </v-col>
                            <v-col
                                cols="12"
                            >
                            <span class="my-4 text-subtitle-1"> Subject Code</span>
                            <v-text-field
                                v-model="formData.code"
                                label="Enter subject's code"
                                :rules="reqrules"
                                outlined
                                
                                flat
                            ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                            >
                            <span class="my-4 text-subtitle-1"> Subject Title</span>
                            <v-text-field
                                v-model="formData.title"
                                label="Enter subject's name"
                                :rules="reqrules"
                                outlined
                                
                                flat
                            ></v-text-field>
                            </v-col>
                             <v-col
                                cols="12"
                            >
                            
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
            dialog: this.show,
            gradeSelection:['7','8','9','10'],
            reqrules:[
                 value =>  !! value || "This is required",
                ],
            formData:{
                grade_level: '',
                units: '',
                code: '',
                title: ''
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
                    setTimeout(()=>{
                    this.clearForm();
                    this.$emit('close');
                     }, 1000)
                }
            },
        data:{
            handler(data){
            if(Object.keys(data).length > 0){
                let {code , title, units, grade_level} = data
                this.formData = {
                    code , 
                    title, 
                    units, 
                    grade_level: grade_level.grade_level}
            }
            },
            deep: true,
            immediate: true,
            },
    },
    methods:{
        clearForm(){
            this.$refs.formData.reset()
            this.formData ={
                grade_level: '',
                units: '',
                code: '',
                title: ''
            }
            this.dialog = false
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate()
            if(formStatus){
                    if(this.action === "add"){
                         this.$store.dispatch('subject/CREATE', this.formData);
                    }
                    if(this.action === "edit"){

                        const payload = {
                            id: this.data.id,
                            subject: this.formData
                        }
                         this.$store.dispatch('subject/EDIT', payload);   
                    }
                    this.clearForm();
            }
        }
    }
}
</script>

<style lang="sass" scoped>

</style>