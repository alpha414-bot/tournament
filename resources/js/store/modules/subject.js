import {SubjectService} from '../../services'

const state = {
    subjects: [],
    message:'',
}

const getters = {}


const mutations = {
    SET_SUBJECTS: (state,payload) => state.subjects = payload,
    SET_UPDATED_SUBJECTS: (state, payload) =>{
        const index = state.subjects.findIndex(subject => subject.id === payload.id);
        // console.log("INDEX",index)
        state.subjects.splice(index, 1, payload)
      },
    SET_MESSAGE: (state,payload) => state.message = payload,
}

const actions = {

    GET_SUBJECTS: async ({commit}, payload) => {
        try{
            let config ={
                params: payload
            }
            const result = await SubjectService.getSubjects(config)
            commit("SET_SUBJECTS", result.data)
        }catch(error){
          console.log(error)
        }
      },

    CREATE: async ({commit,state}, payload) => {
        commit('SET_MESSAGE', '')
        try{
            const result = await SubjectService.createSubject(payload)
            commit('SET_MESSAGE', result.message)
        }catch(error){
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
        }
      },
    EDIT: async ({commit,state}, payload) => {
        commit('SET_MESSAGE', '')
        try{
            const {id, subject} = payload
            const result = await SubjectService.updateSubject({id, subject})
            // console.log("DONE UPDATING SUBJECT",result)
            // commit('SET_UPDATED_SUBJECTS', result.data)
            commit('SET_MESSAGE', result.message)
        }catch(error){
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
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