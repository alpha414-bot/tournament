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
                <p class="text-subtitle-1 ma-0 text-center">{{ this.message }}</p>
            
            </v-card-text>
          
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
        // fullName(){
        //     return `${this.data.first_name} ${this.data.last_name}`
        // },
        title(){
            return 'Message'
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
           
            let userData = this.data
            let payload = { id: userData.id,index: this.index }
            // this.onClose()
            // this.$store.dispatch("section/DELETE_USER", payload)
        },
        onClose(){
            this.dialog = false
        }
    }
}
</script>