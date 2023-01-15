<template>
    <v-card id="main-page" class="pa-5" height="1000%">
        <v-card-title class="title">
            <span>School Year</span>
        </v-card-title>

        <v-card-text>
            <v-row>
                <v-col cols="12" md="7">
                    <v-row>
                        <v-col cols="12" md="7">
                            <v-text-field v-model="search" placeholder="Search Year" prepend-inner-icon="mdi-magnify" hide-details outlined dense></v-text-field>
                        </v-col>
                    </v-row>
                </v-col>
                <v-spacer></v-spacer>

                <v-col cols="12" md="2" class="text-right">
                    <v-btn class="text-capitalize" color="green" outlined elevation="0" height="39" @click="action({action:'add'})">
                        <v-icon left>mdi-plus-box-outline</v-icon>
                        <span>New School Year</span>
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text>
            <v-data-table :headers="dataTable.headers" :items="schoolyears">
                <template v-slot:item.actions="{ item, index }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{on, attrs}">
                            <v-icon class="" v-on="on" v-bind="attrs" @click="action({action: 'edit', data: item, index})">mdi-playlist-edit</v-icon>
                        </template>
                        <span>Edit School Year</span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <template v-slot:activator="{on, attrs}">
                            <v-icon v-on="on" v-bind="attrs" @click="action({action: 'delete', data:item, index})">mdi-delete</v-icon>
                        </template>
                        <span>Delete School Year</span>
                    </v-tooltip>
                </template>
            </v-data-table>
        </v-card-text>
        <school-form v-bind="schoolForm" @close="closeDialog" @edit="action({ ...schoolForm, action: 'edit', show:true})"></school-form>
        <confirm-form v-bind="confirmForm" @close="closeDialog"></confirm-form>
    </v-card>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import SelectionMixins from '../../../mixins/selectionMixins.js'
import schoolForm from './forms/schoolyear-form.vue'
import confirmForm from './forms/confirm-form.vue'
export default {
    name: 'class',
    components: {
        schoolForm,
        confirmForm
    },
    mixins: [SelectionMixins],
    data(){
        return {
            search: '',
            gradeLevel:'All',
            section:'All',
            dataTable:{
                headers:[
                    {text:'ID Numbber',value:'value',width:'5%'},
                    {text:'Year',value:'key',width:'10%'},
                    {text:'Action', value:"actions",width:'5%'}
                ]
            },
            schoolForm: { show: false, action: '', data: {} },
            confirmForm: { show: false, action: '', data:{} },
        }
    },
    computed:{
        ...mapState("schoolyear",["schoolyears", "message"]),
    },
    watch:{
        search(){
            setTimeout(()=>{
                this.GET_SCHOOLYEAR_DATA()
            }, 600)
        },
        message(message){
            if(message!=''){
                this.action({action: 'confirm', data:{}, index: '', message})
            }
        }
    },
    mounted(){
        this.GET_SCHOOLYEAR_DATA()
    },
    methods: {
        ...mapActions("schoolyear",["GET_YEARS", "DELETE"]),
        action({action, data, index, message}){
            switch(action){
                case 'add':
                    this.schoolForm = {show: true, action, data:{}}
                    break
                case 'edit':
                    this.schoolForm = {show: true, action, data, index}
                    break
                case 'delete':
                    this.DELETE({id:data.value})
                    // this.confirmForm = {show:true, action, data, index}
                    break
                case 'confirm':
                    this.confirmForm = {show:true, action, data, index, message}
                    break
            }
        },
        GET_SCHOOLYEAR_DATA(){
            let payload={}
            if(this.search!==''){
                payload.search = this.search
            }
            this.GET_YEARS(payload);
        },
        closeDialog(){
            this.schoolForm = { ...schoolForm, show: false, data:{}}
            setTimeout(()=>{
                this.confirmForm = { show: false, action: '', data:{} }
            }, 5800)
            this.GET_SCHOOLYEAR_DATA()
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