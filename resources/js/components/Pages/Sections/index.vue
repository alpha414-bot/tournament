<template>
    <v-card id="main-page" class="pa-5" height="100%">
        <v-card-title class="title">
            <span>Sections Management</span>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12" md="4">
                    <v-text-field
                    v-model="search"
                    placeholder="Search section name"
                    prepend-inner-icon="mdi-magnify"
                    hide-details
                    outlined
                    dense
                    >
                    </v-text-field>
                </v-col>
                <v-spacer></v-spacer>
                <v-col cols="12" md="2" class="text-right">
                    <v-btn 
                        class="text-capitalize"
                        color="green" 
                        outlined
                        elevation="0" 
                        height="39"
                        @click="action({ action: 'add' })"
                        >
                        <v-icon left> mdi-clipboard-list-outline </v-icon>
                        <span>Add Section</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
        <v-card-text>
            <v-data-table
                :headers="dataTable.headers"
                :items="sections"
                >
                <template v-slot:[`item.actions`]="{ item, index}">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        small
                        class="mr-3"
                        v-on="on"
                        v-bind="attrs"
                        @click="action({ action: 'edit', data: item, index })"
                        >
                        mdi-pencil
                        </v-icon>
                    </template>
                    <span>Edit</span>
                </v-tooltip>
                 <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-icon
                        v-on="on"
                        v-bind="attrs"
                        @click="removeSection(item.id)"
                        >
                        mdi-delete-forever
                        </v-icon>
                    </template>
                <span>Remove Section</span>
                </v-tooltip>
                <!-- <v-btn
                    class="text-capitalize" 
                    color="#1E88E5"
                    elevation="0" 
                    outlined
                    height="39"
                    @click="viewDetails(item.id)"    
                    >View Section</v-btn> -->
                </template>
             </v-data-table>
        </v-card-text>
        <section-form 
          v-bind="sectionForm"  
          @close="closeDialog"
          @edit="action({ ...sectionForm, action: 'edit', show: true })"
        >
        </section-form>
        <confirm-form
        v-bind="confirmForm"
        @close="closeDialog"
        >
        </confirm-form>
    </v-card>
</template>

<script>
import {mapState,mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import SectionForm from './forms/section-form.vue';
import ConfirmForm from './forms/confirm-form.vue';
export default {
    name:'class',
    components:{
        SectionForm,
        ConfirmForm
    },
    data(){
        return{
            dataTable:{
                headers:[
                    {text:'Section',value:'section',width:'5%'},
                    {text:'Grade',value:'grade_level.grade_level',width:'5%'},
                    {text:'Actions',value:'actions',align:'center',width:'2%',sortable: false},
                    
                ]
            },
            sectionForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
            search:'',
        }
    },
    computed:{
        ...mapState("section",["sections","message"])
    },
    watch:{
        search(to){
            // if(to){
                setTimeout(()=>{
                this.GET_SECTIONS_DATA()
                },600)
            // }
        },
        message(message){
            if(message != ''){
                this.action({action: 'confirm', data:{}, index: '',message})
            }
        },
    },
    mounted(){
        this.GET_SECTIONS_DATA()
    },

    methods:{
        ...mapActions("section",["GET_SECTIONS"]),
        GET_SECTIONS_DATA(){
            const payload = {}
            if(this.search.length > 0){
                payload.search = this.search
            }
            this.GET_SECTIONS(payload)
        },
        action({action, data, index, message}){
          switch(action){
                case 'add':
                
                this.sectionForm = {show: true, action, data:{}}
                break
                case 'edit':
                    // data.status === 'DELETED' ?
                    // this.confirmForm = {show: true, action, data, index} :
                    this.sectionForm = { show: true, action, data , index}
                break;
                case 'view':
                this.sectionForm = { show: true, action, data , index}
                break
                case 'delete':
                this.confirmForm = {show: true, action, data, index}
                break
                case 'confirm':
                this.confirmForm = {show: true, action, data, index,message};
                break
            }
        },
        viewDetails(id)
        {
          const path = `/admin/sections/${id}`

          this.$router.push(path)
        },
        closeDialog(){
            this.sectionForm = { show: false, action: '', data:{} }
            this.confirmForm = { show: false, action: '', data:{} }
            const payload = {}
            this.GET_SECTIONS(payload)
        },
        removeSection(id){
            this.$store.dispatch("section/REMOVE_SECTION", id)
        },
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