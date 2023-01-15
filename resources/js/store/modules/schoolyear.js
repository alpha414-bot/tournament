import {SchoolYearService} from '../../services'

const state = {
    schoolyears: [],
    message: '',
}

const getters={}

const mutations = {
    SET_YEAR: (state,payload)=>state.schoolyears=payload,
    SET_UPDATE_YEAR: (state, payload)=>{
        const index = state.schoolyears.findIndex(schoolyears=>schoolyears.id === payload.id)
        state.schoolyears.splice(index, 1, payload)
    },
    SET_MESSAGE: (state,payload)=>state.message=payload,
}

const actions = {
    GET_YEARS: async({commit}, payload)=>{
        try {
            let config = {params: payload}
            const result = await SchoolYearService.getSchoolYear(config)
            commit('SET_YEAR', result.data)
        } catch (error) {
            console.log(error)
            commit('SET_MESSAGE', 'Failed to receive school year data.')
        }
    },

    CREATE: async({commit, state}, payload)=> {
        commit('SET_MESSAGE', '')
        try{
            const result =  await SchoolYearService.createSchoolYear(payload)
            commit('SET_MESSAGE', result.message)
        }catch(error){
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString();
            commit('SET_MESSAGE', message)
        }
    },

    EDIT: async({commit, state}, payload)=>{
        commit('SET_MESSAGE', '')
        try{
            const {id, data} = payload
            const result = await SchoolYearService.updateSchoolYear({id, data})
            commit('SET_MESSAGE', result.message)
        }catch(error){
            let message = error.response.data.message?error.response.data.message: Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
        }
    },
    
    DELETE: async({commit, state}, payload)=>{
        commit('SET_MESSAGE', '')
        try {
            const {id} = payload
            const result = await SchoolYearService.removeSchoolYear({id})
            commit('SET_MESSAGE', result.message)
        } catch (error) {
            let message = error.response.data.message?error.response.data.message: Object.values(error.response.data)[0].toString()
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