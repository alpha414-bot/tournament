import {SectionService} from '../../services'

const state = {
    sections: [],
    message:'',
}

const getters = {
   
}

const mutations = {
    SET_SECTION: (state,payload) => state.section = payload,
    SET_SECTIONS: (state,payload) => state.sections = payload,
    SET_UPDATED_SECTION: (state, payload) =>{
        const index = state.sections.findIndex(section => section.id === payload.id);
        state.classes.splice(index, 1, payload)
      },
    SET_MESSAGE: (state,payload) => state.message = payload,
    REMOVE_SECTION: (state, id) => {
        state.sections = state.sections.filter(section => section.id !== id)
      },
}

const actions = {
    GET_SECTION: async ({commit}, payload) => {
        try{
            let config = {
                params: payload
            }
            const result = await SectionService.getSection(config)
            commit("SET_SECTION", result.data)
        }catch(error){
          console.log(error)
        }
      },
    GET_SECTIONS: async ({commit}, payload) => {
        try{
            let config = {
                params: payload
            }
            const result = await SectionService.getSections(config)
            commit("SET_SECTIONS", result.data)
            
        }catch(error){
          console.log(error)
        }
      },
    ADD_SECTION: async ({commit}, payload) => {
       
        try{
          commit('SET_MESSAGE', '')
            const result = await SectionService.addSection(payload)
            commit('SET_MESSAGE', result.message)
        }catch(error){

            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
        }
    },
    EDIT_SECTION: async ({commit}, payload) => {
        // commit('SET_MESSAGE', '')
        try{
            const {section_id} = payload
            const result = await SectionService.editSection(section_id , payload)
            // commit('SET_UPDATED_SECTION', result.data)

        }catch(error){
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
        }
    },
    REMOVE_SECTION: async({commit}, id) => {
        try{
          const result = await SectionService.removeSection(id)
          commit("REMOVE_SECTION", id);
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