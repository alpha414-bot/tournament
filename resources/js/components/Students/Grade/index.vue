<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>My Class Grades</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                            <v-col  cols="12" md="4">
                                <v-select
                                v-model="school_year_id"
                                :items="get_sy_selection"
                                item-text="key"
                                item-value="value"
                                label="Select school year"
                                outlined
                                dense
                                flat
                                ></v-select>
                            </v-col>
                            <!-- <v-col  cols="12" md="4">
                                <span class="my-4 text-subtitle-1"> Grade Level </span>
                                <v-select
                                v-model="grade_level_id"
                                :items="grade_level_selection"
                                item-text="key"
                                item-value="value"
                                label="Select grade level"
                                outlined
                                dense
                                
                                flat
                                @change="get_sections_subjects_data"
                                ></v-select>
                            </v-col> -->
                            <!-- <v-col  cols="12" md="4">
                                <span class="my-4 text-subtitle-1"> Section </span>
                                <v-select
                                v-model="section_id"
                                :items="sections_selection"
                                item-text="key"
                                item-value="value"
                                label="Select section"
                                outlined
                                dense
                                
                                flat
                                ></v-select>
                            </v-col> -->
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>
                
               <v-col cols="12" md="2">
                    <v-btn
                    class="text-capitalize"
                    dark
                    color="#04C35C"
                    elevation="0"
                    height="39"
                    block
                    @click="generatePDF"> 
                        <v-icon left>
                            mdi-file-download-outline
                        </v-icon> 
                        <span class="">Download PDF</span> 
                    </v-btn>
                    </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="grades"
                >
                <template v-slot:[`item.actions`]="{ item, index }">
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        small
                        class="mr-3"
                        v-on="on"
                        v-bind="attrs"
                        @click="viewDetails(item)"
                        >
                        mdi-eye
                        </v-icon>
                    </template>
                    <span>View students</span>
                </v-tooltip>
                </template>
             </v-data-table>
        </v-card-text>
    </v-card>
</template>

<script>
import {mapState, mapGetters, mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
// import classForm from './forms/class-form.vue';
import LocalStorageMixins from '../../../mixins/localStorage.js'
import jsPDF from 'jspdf'
import 'jspdf-autotable'
export default {
    name:'class',
    // components:{
    //     classForm
    // },
    mixins: [SelectionMixins, LocalStorageMixins],
    data(){
        return{
            search:'',
            school_year_id:'',
            grade_level_id:'',
            section_id:'All',
            dataTable:{
                headers:[
                    {text:'Subject',value:'subject'},
                    {text:'1st',value:'first'},
                    {text:'2nd',value:'second'},
                    {text:'3rd',value:'third'},
                    {text:'4th',value:'fourth'},
                    {text:'Total Average',value:'total_average'},
                    // {text:'Action', value:"actions",sortable: false, align:'center'}
                    
                ]
            },
            classForm: { show: false, action: '', data: {} },
        }
    },
    computed:{
        ...mapState("auth",["user"]),
        ...mapState("classList",[,"classes","sy_selection","grade_level_selection","sections_selection","subjects_selection"]),
        ...mapState("student", ["grades"]),
        ...mapGetters("classList",["get_sy_selection"]),
        heading(){
            let heading = "Subject Grades Record of " + this.user.first_name + " " + this.user.last_name
            return heading
        }
    },
    watch:{
        // search(){
        //     setTimeout(()=>{
        //         this.GET_CLASSES_DATA()
        //     },600)
        // },
        school_year_id(to){
            this.GET_CLASSES_DATA()
        },
        // grade_level_id(to){
        //    this.GET_CLASSES_DATA()
        // },
        // section_id(to){
        //    this.GET_CLASSES_DATA()
        // },
        // user(){
        //     this.GET_CLASSES_DATA()
        // }
    },
    mounted(){
        this.initialize()
    },
    methods:{
        ...mapActions("student",["GET_GRADES"]),
         initialize(){
            this.school_year_id='all'
            setTimeout(()=>{this.GET_CLASSES_DATA()}, 1000) 
        },
        get_sections_subjects_data(){
            let payload = {
                grade_level_id: this.grade_level_id
            }
            this.GET_SECTIONS_SELECTION(payload)
            // this.GET_SUBJECTS_SELECTION(payload)
        },
        GET_CLASSES_DATA(){
            let payload = {
                student_id: this.user.id
            }
            if(this.school_year_id !== 'all' || this.school_year_id === ''){
                payload.school_year_id = this.school_year_id
            }
            // if(this.grade_level_id !== 'All' || this.grade_level_id === ""){
            //     payload.grade_level_id = this.grade_level_id
            // }
            
            // if(this.section_id !== 'All' || this.section_id === ""){
            //     payload.section_id = this.section_id
            // }
            if(payload.student_id){
                this.GET_GRADES(payload)
            }
        },
        viewDetails(item)
        {
            this.SET_DATA(item)
            const path = `/teacher/classes/${item.id}/students`
            this.$router.push(path)
        },
        closeForm(){
            this.classForm.show = false
            this.classForm.data = {}
        },
        SET_DATA(item){
            this.SET_LOCALSTORAGE_DATA('class', item)
        },
        generatePDF() {
            const columns = [
            { title: "Subject", dataKey: "subject" },
            { title:'1st',      dataKey:'first'},
            { title:'2nd',      dataKey:'second'},
            { title:'3rd',      dataKey:'third'},
            { title:'4th',      dataKey:'fourth'},
            { title:'Total Average',dataKey:'total_average'},
            ];
            const doc = new jsPDF({
            orientation: "portrait",
            unit: "in",
            format: "letter"
            });
            // text is placed using x, y coordinates
            doc.setFontSize(16).text(this.heading, 0.5, 1.0);

           
            // create a line under heading
            doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);
            // Using autoTable plugin
            doc.autoTable({
            columns,
            body: this.grades,
            margin: { left: 0.5, top: 1.25 }
            });

            //  doc.save(`${this.heading}.pdf`);
            // // Using array of sentences
            // // doc
            // // .setFont("helvetica")
            // // .setFontSize(12)
            // // .text(this.moreText, 0.5, 3.5, { align: "left", maxWidth: "7.5" });
            
            // // Creating footer and saving file
            doc
            .setFont("times","italic","normal")
            .setFontSize(11)
            .setTextColor(0, 0, 0)
            .text(
                process.env.MIX_SYSTEM_NAME,
                0.5,
                doc.internal.pageSize.height - 0.5
            )
            doc.save(`${this.heading}.pdf`);
        }
    }
}
</script>

<style lang="scss" scoped>
#main-page{
    .add-button {
    height: 39px;
    background-color: #04c35c;
    text-transform: none;
    // width: 168px;
    // font-size: 12px;
    // font-weight: 400;
    color: #fff;
    .v-icon {
      color: #fff;
    }
  }
}
</style>