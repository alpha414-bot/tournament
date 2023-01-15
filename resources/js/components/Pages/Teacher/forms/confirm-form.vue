<template>
      
        <v-dialog
        v-model="dialog"
        max-width="540"
        id="confirm-form"
        persistent
        >
        <v-card min-height="200">
            <v-toolbar color="#B71C1C" dark flat>
                <v-spacer></v-spacer>  
                <v-toolbar-title class="ml-5"><span>{{ title }}</span></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip  bottom>
                <template v-slot:activator="{ on }">
                    <v-icon align="left" v-on="on" @click="dialog = false">mdi-close</v-icon>
                </template>
                <span>Close</span>
                </v-tooltip>
            </v-toolbar>
           
            <v-card-text class="pa-10">
                <p v-if="action === 'delete'" class="text-subtitle-1 ma-0 text-center">{{classLoad}}</p>
                <p v-else class="text-subtitle-1 ma-0 text-center">{{ this.message }}</p>
                
            </v-card-text>
            <v-card-actions class="px-10" v-if="action === 'delete'">
            <v-container>
                <v-row class="px-2 pb-5 justify-center">
                     <v-btn 
                        class="pa-5"
                        color="#A4A6B3"
                        width="150"
                        depressed
                        dark
                        @click="onClose"
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
                        <span class="text-capitalize"> Confirm</span>
                    </v-btn>
            </v-row>
            
            </v-container>
           
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props:{
        show: { type: Boolean, default: false, required: true },
        action: { type: String, default: '' },
        data: { type: Object, default: new Object() },
        index: { default:''},
        message: {type:String, default:''}
        },
    data(){
        return{
            dialog: this.show,
            // isDeleted: false
        }
    },
    computed: {
        classLoad(){
            return `Are you sure you want to remove ${this.data.subjects.title} of ${this.data.name}?` 
        },
        title(){
                return this.action != 'delete' ? 'Message' : 'Remove Class'
            }
      },
    watch:{
        show(value) {
            this.dialog = value;
        },
        dialog(value){
            if (!value) {
                this.$emit('close')
            }
        },
    },
    methods: {
        handleSubmit(){
           
            let payload = { 
                id: this.data.id,
                data: {teacher_id: "null"},
                index: this.index
                }
            
            this.$store.dispatch("teacher/UPDATE_CLASS", payload)
            this.onClose()
        },
        onClose(){
            this.dialog = false
        }
    }
}
</script>