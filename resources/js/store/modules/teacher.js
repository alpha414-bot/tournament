import {TeacherService} from '../../services'

const state = {
    teacher: '',
    teachers: [],
    classes: [],
    message:'',
    subjects:[]
}

const getters = {}

const mutations = {
    SET_TEACHER: (state,payload) => state.teacher = payload,
    SET_TEACHERS: (state,payload) => state.teachers = payload,
    SET_CLASSES: (state,payload) => state.classes = payload,
    SET_UPDATED_TEACHERS: (state, payload) =>{
        const index = state.teachers.findIndex(teachers => teachers.id === payload.id);
        state.teachers.push(index, 1, payload)
      },
    SET_MESSAGE: (state,message) => state.message = message,
    SET_NEW_SUBJECTS: (state, payload) =>{
      payload.map((subject)=>{
        const index = state.classes.findIndex(subjectclass => subjectclass.id === subject.id)
        if(index){
          state.classes.unshift(subject)
        }
      })
    },
    REMOVE_SUBJECT: (state, id) => {
      state.classes = state.classes.filter(subject => subject.id !== id)
    },
}

const actions = {

    //TEACHERS
    GET_TEACHERS: async ({commit}, payload) => {
        try{
          const config = {
            params: payload
          }
          const result = await TeacherService.getTeachers(config)
          commit("SET_TEACHERS", result.data);

        }catch(error){
          console.log(error)
        } 
    },
    GET_TEACHER:  async ({commit}, id) => {
      try{
        const result = await TeacherService.getTeacher(id)
        const data = result.data ? result.data : []
        commit("SET_TEACHER", data)
      }catch(error){
        console.log(error)
      } 
    },
    GET_CLASSES:  async ({commit}, payload) => {
      try{
        const config = {
          params: payload
        }
        const result = await TeacherService.getClasses(config, payload.id)
        commit("SET_CLASSES", result.data)
      }catch(error){
        console.log(error)
      } 
    },
    CREATE_TEACHER: async ({commit,state}, payload) => {
        commit('SET_MESSAGE', '')
          try{
            const result = await TeacherService.createTeacher(payload)
            state.teachers.push(payload)
            commit('SET_MESSAGE', result.message)
          }catch(error){
            console.log(error)
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
        
            commit('SET_MESSAGE', message)
          } 
        },
    UPDATE_TEACHER: async ({commit}, payload) => {

      commit('SET_MESSAGE', '')
      try{
        const {id, user} = payload

        const result = await TeacherService.updateTeacher(id, user)
        
        const data = result.data ? result.data : []
        commit("SET_TEACHER", result.data)
        commit('SET_MESSAGE', result.message)
      }catch(error){

        let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
        
        commit('SET_MESSAGE', message)
      } 
    },
    UPDATE_CLASS: async ({commit, state}, payload) => {

      commit('SET_MESSAGE', '')
      try{
        const {id, data, index} = payload

        console.log("UPDATING", payload)

        const result = await TeacherService.updateClass(id, data)
        console.log(result)
        state.classes.splice(index,1)
        // commit("SET_TEACHER", result.data)
        commit('SET_MESSAGE', result.message)
      }catch(error){

        let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
        
        commit('SET_MESSAGE', message)
      } 
    },
    ASSIGN_SUBJECTS: async({commit}, payload) => {
      try{
        const result = await TeacherService.assignSubjects(payload)
        // console.log(result)
        commit("SET_NEW_SUBJECTS", result.data);
      }catch(error){
        console.log(error)
      } 
    },
    REMOVE_SUBJECT: async({commit}, id) => {
      try{
        const result = await TeacherService.removeSubject(id)
        commit("REMOVE_SUBJECT", id);
      }catch(error){
        console.log(error)
      } 
    }
    

}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}