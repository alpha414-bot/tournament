import {UserService} from '../../services'

const state = {
    user: '',
    users: [],
    classes: [],
    message:'',
    me:{},
};

const getters = {}

const mutations = {
    SET_USER_DATA: (state,payload) => state.user = payload,
    SET_TEACHERS: (state,payload) => state.users = payload,
    SET_CLASSES: (state,payload) => state.classes = payload,
    SET_UPDATED_USERS: (state, payload) =>{
      const index = state.users.findIndex(user => user._id === payload._id);
      state.users.splice(index, 1, payload)
    },
    SET_MESSAGE: (state,message) => state.message = message,
    UPDATE_USER_DATA: (state, payload) => state.user = payload,
}

const actions = {

    GET_USER_PROFILE: async ({commit}) => {
        try{
          const result = await UserService.getUserProfile()
          console.log(result);
          commit("SET_USER_DATA", result.data)
        }catch(error){
          console.log(error)
        }
      },

    CREATE_USER: async ({commit}, payload) => {
      commit('SET_MESSAGE', '')
        try{
      
          const result = await UserService.createUser(payload)
          commit('SET_UPDATED_USERS',result.data)
          commit('SET_MESSAGE', result.data)
        }catch(error){
          console.log(error)
        } 
      },

    //TEACHERS
    GET_TEACHERS: async ({commit}, payload) => {
        try{
          
          const config = {
            params: payload
          }

          const result = await UserService.getTeachers(config)
          commit("SET_TEACHERS", result.data);

        }catch(error){
          console.log(error)
        } 
    },
    GET_TEACHER:  async ({commit}, id) => {
      try{
        const result = await UserService.getTeacher(id)
        const data = result.data ? result.data : []
        commit("SET_USER_DATA", data)
        commit("SET_CLASSES", data.class_lists)
      }catch(error){
        console.log(error)
      } 
    },
    UPDATE_USER: async ({commit,dispatch}, payload) => {

      commit('SET_MESSAGE', '')
      try{
        const {id, user} = payload
        const result = await UserService.updateUserById(id, user)
        commit("UPDATE_USER_DATA", result.data)
        dispatch('GET_USER_PROFILE');
        // commit('SET_MESSAGE', result.message)
      }catch(error){
        console.log(error)
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