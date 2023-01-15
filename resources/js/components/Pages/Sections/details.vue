<template>
      <v-card id="main-page" class="pa-5" height="100%">
        <v-btn class="pl-0" plain text @click="goBack()"><v-icon>mdi-chevron-left</v-icon> Back</v-btn>
        
        
        <v-card-text>
             <v-row>
                <v-col cols="12" md="7">
                    <!-- <v-row>
                        <v-col cols="12" md="6">
                            <v-text-field
                            v-model="search"
                            placeholder="Search subject"
                            prepend-inner-icon="mdi-magnify"
                            hide-details
                            outlined
                            dense
                            >
                            </v-text-field>
                        </v-col>
                    </v-row> -->
                    <v-card-title> Class {{className}}</v-card-title>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="12" md="2" class="text-right">
                     <v-btn 
                        class="text-capitalize" 
                        color="success"
                        elevation="0" 
                        outlined
                        height="39"
                         @click="action({ action: 'addSubjects' })"
                        >
                        <v-icon left> mdi-plus-box-outline </v-icon>
                        <span>Add Subjects</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.classHeaders"
                :items="classes"
                 hide-default-footer
                >
                <template v-slot:item.actions="{ item, index }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                            class=""
                            v-on="on"
                            v-bind="attrs"
                            @click="action({ action: 'edit', data: item, index })"
                            >
                            mdi-playlist-edit
                            </v-icon>
                        </template>
                        <span>Edit</span>
                    </v-tooltip>
                </template>
                
             </v-data-table>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                <v-card-title> Students </v-card-title>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="12" md="5" class="mt-3 text-right">
                        <v-btn 
                            class="text-capitalize" 
                            color="success"
                            elevation="0" 
                            outlined
                            height="39"
                            @click="action({ action: 'addStudents' })"
                            >
                            <v-icon left> mdi-plus-box-outline </v-icon>
                            <span>Add Students</span>
                        </v-btn>
                    </v-col>
            </v-row>
            
        </v-card-text>
        
        <v-card-text>
            <v-data-table
                :headers="dataTable.studentHeaders"
                :items="students"
                 hide-default-footer
                >
                 <template v-slot:item.actions="{ item, index }">
                    
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                            class=""
                            v-on="on"
                            v-bind="attrs"
                            @click="action({ action: 'edit', data: item, index })"
                            >
                            mdi-playlist-edit
                            </v-icon>
                        </template>
                        <span> </span>
                    </v-tooltip>
                </template>
                
             </v-data-table>
        </v-card-text>
        <class-form 
          v-bind="classForm"  
          @close="closeDialog"
          @edit="action({ ...classForm, action: 'edit', show: true })"
        >
        </class-form>
        <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import classForm from './forms/class-form.vue';
import confirmForm from './forms/confirm-form.vue';
export default {
    name: 'section-details',
    components: {
        classForm,
        confirmForm,
    },
    // mixins:[LocalStorageMixins],
    data() {
        return {
            dataTable:{
                classHeaders:[
                    {text:'Class',value:'name'},
                    {text:'Subject',value:'title',cellClass:'text-capitalize'},
                    {text:'Days',value:'days'},
                    {text:'Time',value:'time'},
                    {text:'Teacher',value:'teacher'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}
                ],
                studentHeaders:[
                    {text:'ID',value:'learners_number'},
                    {text:'First Name',value:'first_name',cellClass:'text-capitalize'},
                    {text:'Last Name',value:'last_name'},
                ],
                data:[]
            },
            classForm: { show: false, action: '', data: {} },
            pageLimit: 10,
            pageSelection:[10,20,50,100],
            page: 1,
            totalPage:0,
            search:'',
            timer:'',
            isLoading:false,
            showPagination: false,
            classForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
        }
    },
    computed: {
        // ...mapState("teacher",["teacher","classes","message"]),
        ...mapState("section",["section","classes","message","students"]),
        
        className(){
            let data = this.section
            let grade = data.grade_level? data.grade_level.grade_level : " "
            let section = data? data.section : " "
            return `${grade} - ${section}`
        },
        fullName(){
            return `${this.teacher.first_name} ${this.teacher.last_name}`
        },
    },
    watch:{
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
    },
    mounted(){
      
      this.initialize()
       
    },
    methods: {
      ...mapActions("teacher", ['GET_TEACHER']),
      ...mapActions("section", ['GET_SECTION','GET_CLASSES']),
        initialize(){
             this.GET_TEACHER(this.$route.params.id)
             this.getSectionData()
        },
        getSectionData(){
            let payload = {
                id: this.$route.params.id
            }
            this.GET_SECTION(payload)
            this.GET_CLASSES(this.$route.params.id)
        },
        goBack(){
            this.$router.go(-1)
        },
        action({action, data, index, message}){

            switch(action){
                case 'addSubjects':
                this.classForm = {show: true, action, data: this.section}
                break
                case 'addStudents':
                this.classForm = {show: true, action, data: this.section}
                break
                case 'edit':
                    this.classForm = { show: true, action, data: data, index}
                    // console.log({action, data, index, message})
                break;
                case 'view':
                this.classForm = { show: true, action, data , index}
                break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index, message};
                break
            }
        },
        closeDialog(){
            this.classForm = { show: false, action: '', data:{} }
            this.confirmForm = { show: false, action: '', data:{} }
            this.GET_CLASSES(this.$route.params.id)
        }

    },
}
</script>

<style lang="scss" scoped>
// @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

</style>
