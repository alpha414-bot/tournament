<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>My Class List</span>
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
                        <v-col  cols="12" md="4">
                            <v-select
                            v-model="grade_level_id"
                            :items="get_grade_level_selection"
                            item-text="key"
                            item-value="value"
                            label="Select grade level"
                            outlined
                            dense
                            flat
                            @change="get_sections_subjects_data"
                            ></v-select>
                        </v-col>
                        <v-col  cols="12" md="4">
                            <v-select
                            v-model="section_id"
                            :items="get_sections_selection"
                            item-text="key"
                            item-value="value"
                            label="Select section"
                            outlined
                            dense
                            flat
                            ></v-select>
                        </v-col>
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="12" md="2">
                    <CSV
                        :json-data="classes"
                        csv-title="Class List"
                        :labels="{
                            section:{ title:'Section'},
                            subject:{title:'Subject'},
                            days:{title:'Days'},
                            start_time:{title:'Start Time'},
                            end_time:{title:'End Time'}
                        }"
                    >
                    <v-btn
                    right
                    class="text-capitalize"
                    dark
                    color="#04C35C"
                    elevation="0"
                    height="39"
                    block> 
                        <v-icon left>
                            mdi-file-download-outline
                        </v-icon> 
                        <span class="">Download CSV</span> 
                    </v-btn>
                    </CSV> 
                </v-col>
                <v-col cols="12" md="2">
                    <v-btn
                    right
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
                :items="classes"
                >
                <template v-slot:[`item.actions`]="{ item }">
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
        <class-form 
          v-bind="classForm"  
          @close="closeForm"
          @edit="action({ ...classForm, action: 'edit', show: true })"
        >
        </class-form>
    </v-card>
</template>

<script>
import {mapState, mapActions, mapGetters} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import classForm from './forms/class-form.vue';
import LocalStorageMixins from '../../../mixins/localStorage.js'
import CSV from 'vue-json-to-csv'
import jsPDF from 'jspdf'
import 'jspdf-autotable'
export default {
    name:'class',
    components:{
        classForm,
        CSV
    },
    mixins: [SelectionMixins, LocalStorageMixins],
    data(){
        return{
            search:'',
            school_year_id:'all',
            grade_level_id:'all',
            section_id:'all',
            dataTable:{
                headers:[
                    {text:'Section',value:'section'},
                    // {text:'Instructor',value:'instructor'},
                    {text:'Subject',value:'subject',cellClass:'text-capitalize'},
                    {text:'Days',value:'days'},
                    {text:'Start time',value:'start_time'},
                    {text:'End time',value:'end_time'},
                    {text:'Action', value:"actions",sortable: false, align:'center'}
                    
                ]
            },
            classForm: { show: false, action: '', data: {} },
        }
    },
    computed:{
        ...mapState("auth",["user"]),
        ...mapState("classList",["classes","sy_selection","grade_level_selection","sections_selection","subjects_selection"]),
        ...mapGetters("classList", ["get_sy_selection","get_grade_level_selection","get_sections_selection"]),
         heading(){
            let heading = "Class List of " + this.user.first_name + " " + this.user.last_name
            return heading
        }
    },
    watch:{
        search(){
            setTimeout(()=>{
                this.GET_CLASSES_DATA()
            },600)
        },
        school_year_id(to){
            this.GET_CLASSES_DATA()
        },
        grade_level_id(to){
           this.GET_CLASSES_DATA()
        },
        section_id(to){
           this.GET_CLASSES_DATA()
        },
        user(){
            this.GET_CLASSES_DATA()
        },
       
    },
    mounted(){
        this.initialize()
    },
    methods:{
        ...mapActions("classList",["GET_CLASSES","GET_SECTIONS_SELECTION"]),
        initialize(){
            this.get_sections_subjects_data()
            this.GET_CLASSES_DATA()
        },
        get_sections_subjects_data(){
            let payload = {}

            if(this.grade_level_id !== 'all' || this.grade_level_id === ""){
                payload.grade_level_id = this.grade_level_id
            }
            this.GET_SECTIONS_SELECTION(payload)
            // this.GET_SUBJECTS_SELECTION(payload)
        },
        GET_CLASSES_DATA(){

            let payload = {}
            if(this.user.role === 'teacher'){
                payload.teacher_id = this.user.id
            }
            if(this.school_year_id !== 'all'){
                payload.school_year_id = this.school_year_id
            }
            if(this.grade_level_id !== 'all' || this.grade_level_id === ""){
                payload.grade_level_id = this.grade_level_id
            }
            
            if(this.section_id !== 'all' || this.section_id === ""){
                payload.section_id = this.section_id
            }
            this.GET_CLASSES(payload)
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
            { title: "Section", dataKey: "section" },
            { title: "Subject", dataKey: "subject" },
            { title:'Subject',  dataKey:'subject',},
            { title:'Days',     dataKey:'days'},
            { title:'Start time',dataKey:'start_time'},
            { title:'End time',dataKey:'end_time'},
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
            body: this.classes,
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
            .setTextColor(0, 0, 255)
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