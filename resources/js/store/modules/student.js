import {StudentService} from '../../services'

const state = {
    student: [],
    students: [],
    classes: [],
    enrollments: [],
    subjects: [],
    grades:[],
    subjects_selection: [],
    message:'',
}

const getters = {}

const mutations = {
    SET_STUDENT: (state,payload) => state.student = payload,
    SET_STUDENTS: (state,payload) => state.students = payload,
    SET_CLASSES: (state,payload) => state.classes = payload,
    SET_ENROLLMENTS: (state,payload) => state.enrollments = payload,
    SET_SUBJECTS: (state,payload) => state.subjects = payload,
    SET_GRADES: (state,payload) => state.grades = payload,
    SET_SUBJECTS_SELECTION: (state,payload) => state.subjects_selection = payload,
    SET_UPDATED_STUDENTS: (state, payload) =>{
        const index = state.students.findIndex(students => students.id === payload.id);
        state.students.push(index, 1, payload)
      },
    SET_NEW_SUBJECTS: (state, payload) =>{
      payload.map((subject)=>{
        state.subjects.unshift(subject)
      })
    },
    SET_NEW_ENROLLMENT: (state, payload) =>{ state.enrollments.unshift(payload)},
    REMOVE_SUBJECT: (state, id) => {
      state.subjects = state.subjects.filter(subject => subject.id !== id)
    },
    SET_MESSAGE: (state,message) => state.message = message,
}

const actions = {

    //STUDENTS
    GET_STUDENTS: async ({commit}, payload) => {
        try{
          const config = {
            params: payload
          }
          const result = await StudentService.getStudents(config)
          commit("SET_STUDENTS", result.data);

        }catch(error){
          console.log(error)
        } 
    },
    GET_STUDENT:  async ({commit}, id) => {
      try{
        const result = await StudentService.getStudent(id)
        const data = result.data ? result.data : []
        commit("SET_STUDENT", data)
        
        
      }catch(error){
        console.log(error)
      } 
    },
    CREATE_STUDENT: async ({commit,state}, payload) => {
        commit('SET_MESSAGE', '')
          try{
            console.log(payload)
            const result = await StudentService.createStudent(payload)
            state.students.push(payload)
            commit('SET_MESSAGE', result.message)
          }catch(error){
            console.log(error)
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
        
            commit('SET_MESSAGE', message)
          } 
        },
    UPDATE_STUDENT: async ({commit}, payload) => {

      commit('SET_MESSAGE', '')
      try{
        const {id, user} = payload

        const result = await StudentService.updateStudent(id, user)
        const data = result.data ? result.data : []
        console.log(data)
        commit("SET_STUDENT", data)
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

        const result = await StudentService.updateClass(id, data)
        console.log(result)
        state.classes.splice(index,1)
        // commit("SET_STUDENT", result.data)
        commit('SET_MESSAGE', result.message)
      }catch(error){

        let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
        
        commit('SET_MESSAGE', message)
      } 
    },

    //Enrollments
    GET_ENROLLMENTS: async ({commit}, payload) => {
      try{
          const {enrollment_id} = payload
          let config = {
            params: payload
          }
          const result = await StudentService.getEnrollments(enrollment_id,config)
          commit("SET_ENROLLMENTS", result.data);

        }catch(error){
          console.log(error)
        } 
    },

    ENROLL_STUDENT: async ({commit}, payload) => {
      try{
        const result = await StudentService.enrollStudent(payload)
        console.log(result)
        commit("SET_NEW_ENROLLMENT", result.data);
      }catch(error){
        console.log(error)
      } 
    },
    // GET_SUBJECTS: async({commit}, id) => {
    //   try{
    //     let config = {
    //       params: {
    //         enrollment_id : id
    //       }
    //     }
    //     const result = await StudentService.getSubjects(config)
    //     commit("SET_SUBJECTS", result.data);
    //   }catch(error){
    //     console.log(error)
    //   } 
    // },
    GET_SUBJECTS_SELECTION: async({commit}, payload) => {
      try{
        let config = {
          params: payload
        }
        const result = await StudentService.getSubjectsSelection(config)
        commit("SET_SUBJECTS_SELECTION", result.data);
      }catch(error){
        console.log(error)
      } 
    },
    ASSIGN_SUBJECTS: async({commit}, payload) => {
      try{
        let config = {
          params: payload
        }
        const result = await StudentService.assignSubjects(config)
        console.log(result)
        commit("SET_NEW_SUBJECTS", result.data);
      }catch(error){
        console.log(error)
      } 
    },
    REMOVE_SUBJECT: async({commit}, id) => {
      try{
        const result = await StudentService.removeSubject(id)
       
        commit("REMOVE_SUBJECT", id);
      }catch(error){
        console.log(error)
      } 
    },
    GET_SUBJECTS: async({commit}, payload) => {
      try{
        let config = {
          params: payload
        }
        const result = await StudentService.getSubjects(config)
        commit("SET_SUBJECTS", result.data);
      }catch(error){
        console.log(error)
      } 
    },
    GET_GRADES: async({commit}, payload) => {
      try{
        let config = {
          params: payload
        }
        const result = await StudentService.getGrades(config)
        commit("SET_GRADES", result.data);
      }catch(error){
        console.log(error)
      } 
    },

}

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters
}