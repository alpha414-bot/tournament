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
                    {{ `${action === 'edit' ? 'Schedule' : action === 'addSubjects' ? 'New Subjects' : 'Student\'s List'}` }}
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
                            <template v-if="action === 'addSubjects'">
                                <v-col cols="12">
                                <span class="my-4 text-subtitle-1"> Subjects</span>
                                <v-combobox
                                    v-model="formData.subjects"
                                    :items="subjectSelection"
                                    item-text="title"
                                    item-value="id"
                                    return-object
                                    clearable
                                    multiple
                                    small-chips
                                    deletable-chips
                                    label="Select subjects"
                                    outlined
                                    dense
                                    :rules="[v => !!v || 'Subject is required']"
                                >
                                </v-combobox>
                                </v-col>       
                            </template>
                            <template v-else-if="action === 'addStudents'">
                                <v-col cols="12">
                                <span class="my-4 text-subtitle-1">Students</span>
                                <v-combobox
                                    v-model="formData.students"
                                    :items="studentSelection"
                                    item-text="name"
                                    item-value="id"
                                    return-object
                                    clearable
                                    multiple
                                    small-chips
                                    deletable-chips
                                    label="Select students"
                                    outlined
                                    dense
                                    height="100"
                                    :rules="[v => !!v || 'Students is required']"
                                >
                                </v-combobox>
                                </v-col>       
                            </template>
                            <template v-else>
                                <v-col cols="12" >
                                <span class="my-4 text-subtitle-1"> Days</span>
                                    <v-combobox
                                    v-model="formData.days"
                                    :items="daysSelection"
                                    clearable
                                    multiple
                                    small-chips
                                    deletable-chips
                                    return-object
                                    single-line
                                    label="Select days"
                                    outlined
                                    dense
                                    :rules="[v => !!v || 'Days is required']"
                                 >
                                    </v-combobox>
                                </v-col> 
                                <v-col cols="12" md="6">
                                    <v-menu
                                        ref="menu"
                                        v-model="menu"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="formData.start_time"
                                            label="Start Time"
                                            prepend-inner-icon="mdi-clock-time-four-outline"
                                            readonly
                                            outlined
                                            v-bind="attrs"
                                            v-on="on"
                                            dense
                                            :rules="[v => !!v || 'Start Time is required']"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="menu"
                                        v-model="formData.start_time"
                                        :max="formData.end_time"
                                        ampm-in-title
                                        :allowed-minutes="[0,15,30,45]"
                                        scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <!-- <v-btn text color="primary" @click="menu = false"> Cancel</v-btn> -->
                                    <v-btn text color="primary" @click=" menu = !menu;"> APPLY </v-btn>
                                    </v-time-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-menu
                                        ref="menu2"
                                        v-model="menu2"
                                        :close-on-content-click="false"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="formData.end_time"
                                            label="End Time"
                                            prepend-inner-icon="mdi-clock-time-four-outline"
                                            readonly
                                            outlined
                                            v-bind="attrs"
                                            v-on="on"
                                            dense
                                            :rules="[v => !!v || 'End Time is required']"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="menu2"
                                        v-model="formData.end_time"
                                        ampm-in-title
                                        :min="formData.start_time"
                                        :allowed-minutes="[0,15,30,45]"
                                        scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <!-- <v-btn text color="primary" @click="menu = false"> Cancel</v-btn> -->
                                    <v-btn text color="primary" @click=" menu2 = !menu2;"> APPLY </v-btn>
                                    </v-time-picker>
                                    </v-menu>
                                </v-col>
                                 <!-- <v-col cols="12" >
                                    <span class="my-4 text-subtitle-1"> Teacher</span>
                                    <v-combobox
                                    v-model="formData.teacher"
                                    :items="teacherSelection"
                                    item-text="name"
                                    item-value="id"
                                    return-object
                                    clearable
                                    small-chips
                                    deletable-chips
                                    single-line
                                    label="Select Teacher"
                                    outlined
                                    dense
                                    :rules="[v => !!v || 'Teacher is required']"
                                 >
                                    </v-combobox>
                                </v-col>  -->
                            </template>
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
                    <span class="text-capitalize">{{this.action ==='view' ? 'Edit' : this.action === 'add' ? 'Add' : 'Save'}}</span>
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
            menu: false,
            menu2: false,
            formData:{
                grade_level: '',
                section: '',
                subjects: '',
                days: '',
                start_time: null,
                end_time: null,
                teacher_id: '',
            },
            requiredRule:[
                 value => !! value || "Required field",
            ],
        }
    },
    computed:{
        ...mapState("section",["subjects","studentSelection"]),
        ...mapGetters("section",["subjectSelection","teacherSelection"]),
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
            if(data.grade_level){
                this.setSelections();
            }
            if(this.action === 'edit' && Object.keys(data).length > 0){
                let {grade_level, section, days, start_time,end_time, instructor, id} = data
                this.formData = {
                    grade_level,
                    section,
                    subjects: '',
                    days: days !== "None" ? days.split('/') : '',
                    start_time: start_time !== "None" ? start_time : null,
                    end_time: end_time !== "None" ? end_time : null,
                    teacher: instructor
                }
            }
            },
            deep: true,
            immediate: true,
            },
        
    },
    methods:{
        ...mapActions("section",["GET_STUDENTS_SELECTION"]),
        setSelections(){
            const payload = {
                grade_level :  this.data.grade_level.grade_level,
                section_id: this.data.id,
            }
            if(this.action === "addSubjects"){
                this.GET_SUBJECTS(payload);
            }
            if(this.action === "addStudents"){
                this.GET_STUDENTS_SELECTION(payload);
            }
          
        },
        clearForm(){
            this.$refs.formData.resetValidation()
            this.formData = {
                grade_level: '',
                section: '',
                subjects: '',
                days: '',
                start_time: null,
                end_time: null,
                teacher: '',
            }
        },
        handleSubmit(){
            let formStatus = this.$refs.formData.validate()
            if(formStatus){
               
                if(this.action == 'addSubjects'){
                    let {subjects} = this.formData
                    let data = {
                        grade_level_id: this.data.grade_level.id,
                        section_id: this.data.id,
                        section: this.data.section,
                        grade_level: this.data.grade_level.grade_level,
                        subjects: subjects
                    }
                    this.$store.dispatch("section/ADD_CLASS", data)
                }
                if(this.action == 'addStudents'){
                    let {students} = this.formData
                    let data = {
                        section_id: this.data.id,
                        students: students
                    }
                    this.$store.dispatch("section/ADD_STUDENTS", data)
                }
                if(this.action == 'edit'){
                    let {days, start_time, end_time } = this.formData
                    let data = {
                        days: days.join('/'), 
                        start_time,
                        end_time, 
                    }
                    
                    let payload = {
                        id: this.data.id,
                        data: data,
                        // index: this.index
                    }
                    this.$store.dispatch("classList/EDIT_CLASS_SCHEDULE", payload)
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