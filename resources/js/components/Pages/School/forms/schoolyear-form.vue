<template>
    <v-dialog id="class-form" v-model="dialog" max-width="1000" persistent>
        <v-card>
            <v-toolbar color="#CB3737" dark flat>
                <v-spacer></v-spacer>
                <v-toolbar-title>
                    {{ `${action == 'view' ? 'View' : action == 'edit' ? 'Edit' : 'Add New'}` }} School Year
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-icon align="left" v-on="on" @click="dialog=false">mdi-close</v-icon>
                    </template>
                    <span>Close</span>
                </v-tooltip>
            </v-toolbar>

            <v-card-text class="px-10 py-7">
                <v-form ref="formData">
                    <v-container class="px-10">
                        <v-row>
                            <v-col cols="12">
                                <span class="my-4 text-subtitle-1">School Year</span>
                                <v-text-field v-model="formData.school_year" label="Enter school year" :rules="reqrules" outlined flat></v-text-field>
                            </v-col>
                            <v-col cols="12"></v-col>
                        </v-row>
                    </v-container>
                </v-form>
            </v-card-text>
            <v-card-actions class="px-10">
                <v-container class="px-10">
                    <v-row class="px-2 pb-5">
                        <v-spacer></v-spacer>
                        <v-btn v-show="action!=='view'" class="pa-5" color="#A4A6B3" width="150" depressed dark @click="dialog=false">
                            <span class="text-capitalize">Cancel</span>
                        </v-btn>
                        <v-btn class="pa-5 ml-3" color="3B71C1C" width="150" depressed dark @click="handleSubmit">
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
        name: 'class-form',
        mixins: [SelectionMixins],
        props: {
            show: {type: Boolean, default: false, required: true },
            action: {type: String, default: ''},
            data: {type: Object, default: new Object()},
            index: { default:''}
        },
        data(){
            return {
                dialog: this.show,
                reqrules: [
                    value => !! value || "This is required",
                ],
                formData: {
                    school_year: ''
                }
            }
        },
        watch:{
            show(value){
                this.dialog = value
            },
            dialog(value){
                if(!value){
                    setTimeout(() => {
                        this.clearForm();
                        this.$emit('close');
                    }, 1000);
                }
            },
            data: {
                handler(data){
                    if(Object.keys(data).length>0){
                        let {key, value, selected} = data
                        this.formData = {
                            school_year: key
                        }
                    }
                },
                deep: true,
                immediate: true
            }
        },
        methods:{
            clearForm(){
                this.$refs.formData.reset()
                this.formData = {
                    school_year: '',
                }
                this.dialog = false
            },
            handleSubmit(){
                let formStatus = this.$refs.formData.validate()
                if(formStatus){
                    if(this.action==="add"){
                        this.$store.dispatch('schoolyear/CREATE', this.formData)
                    }
                    if(this.action==="edit"){
                        const payload = {
                            id: this.data.value,
                            data: this.formData
                        };
                        this.$store.dispatch('schoolyear/EDIT', payload)
                    }
                    this.clearForm();
                }
            }
        }
    }
</script>

<style lang="sass" scoped>

</style>