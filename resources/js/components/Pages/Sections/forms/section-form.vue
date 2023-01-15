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
                    {{ `${action === 'edit' ? 'Edit' : 'Create'}` }} Section
                </v-toolbar-title>
                <v-spacer></v-spacer>
                 <v-tooltip  bottom>
                <template v-slot:activator="{ on }">
                    <v-icon align="left" v-on="on" @click="closeDialog">mdi-close</v-icon>
                </template>
                <span>Close</span>
                </v-tooltip>
            </v-toolbar>
            <v-card-text class="px-10 py-7">
                <v-form ref="formData" >
                    <v-container class="px-10">
                        <v-row>
                            <v-col >
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
                                :rules="[value => !! value || 'Grade Level is required']"
                                ></v-select>
                            </v-col>    
                            <v-col>
                                <span class="my-4 text-subtitle-1">Section</span>
                                <v-text-field
                                    v-model="formData.section"
                                    :rules="[ v => !! v || 'Section Name is required']"
                                    label="Enter section name"
                                    outlined
                                    
                                    flat
                                    dense
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
                    @click="closeDialog"
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
                    <span class="text-capitalize">{{this.action === 'add' ? 'Add' : 'Save'}}</span>
                    </v-btn>
            </v-row>
            </v-container>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import {mapState, mapActions,mapGetters} from 'vuex'
import SelectionMixins from '../../../../mixins/selectionMixins'
export default {
    name:'class-form',
    mixins: [SelectionMixins],
    props:{
        show: { type: Boolean, default: false, required: true },
        action: { type: String, default: '' },
        data: {  default: new Object() },
        index: { default:''}
    },
    data(){
        return{
            dialog: this.show,
            formData:{
                grade_level_id: '',
                section: ''
            },
        }
    },
    computed:{
        ...mapState("section",["subjects","studentSelection"]),
         ...mapState("classList",["grade_level_selection"]),
    },
    watch:{
        show(value) {
                this.dialog = value
            },
        dialog(value){
                 if (!value) {
                    this.clearForm();
                    this.$emit('close');
                }
            },
         data:{
            handler(data){
            if(this.action === 'edit' && Object.keys(data).length > 0){
                let {grade_level_id, section} = this.data
                this.formData = {
                    grade_level_id: grade_level_id,
                    section,
                }
            }
            },
            deep: true,
            immediate: true,
            },
        // formData:{
        //     handler(data){
        //     console.log(data.grade_level_id)
        //     },
        //     deep: true,
        //     immediate: true,
        //     },
    },
    methods:{
        clearForm(){
            this.$refs.formData.reset()
            
            this.formData = {
                grade_level: '',
                section: '',
            }
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate()
            if(formStatus){
                if(this.action == 'add'){
                    let {grade_level_id, section} = this.formData
                    let payload = {
                        grade_level_id,
                        section
                    }
                    this.$store.dispatch("section/ADD_SECTION", payload)
                }
                if(this.action == 'edit'){
                    let {section} = this.formData
                    let payload = {
                        section_id: this.data.id,
                        section
                    }
                    // console.log(payload)
                    this.$store.dispatch("section/EDIT_SECTION", payload)
                }
            this.closeDialog()
            }
        },
        closeDialog(){
            this.dialog = false
        },
        
    }
}
</script>

<style lang="sass" scoped>

</style>