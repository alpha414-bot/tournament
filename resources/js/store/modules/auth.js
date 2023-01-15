import { AuthService } from '../../services';
import router from '../../router';

const state = {
    authToken: localStorage.getItem('token') || null,
    isAuthenticated: false,
    user: [],
    isError: false,
    message:'',
    messageBag:[],
}

const getters = {}

const mutations = {
    SET_TOKEN:  (state, token) => {
        state.authToken = token
        localStorage.setItem('token', token);
      },
    SET_AUTHENTICATED: (state, isAuthenticated) => {
        state.isAuthenticated = isAuthenticated;
    },
    CLEAR_TOKEN: (state) => {
        localStorage.removeItem('token');
        state.authToken = null;
    },
    SET_USER_DATA: (state,payload) => state.user = payload,
    SET_ERROR: (state, isError) => state.isError = isError,
    SET_MESSAGE: (state, message) => state.message = message,
    SET_MESSAGE_BAG: (state, messageBag) => state.messageBag = messageBag,
}

const actions = {
    login: async ({commit,state,dispatch}, payload) => {
        try{
            let { email, password } = payload
           
              const result = await AuthService.login(email, password);

              // setTimeout(()=>{
                commit('SET_TOKEN', result.data.token);
                commit('SET_AUTHENTICATED', true);
                commit('SET_ERROR', false);
                window.location.href = '/';
                // }, 1000)
              //   dispatch('GET_USER_PROFILE')
              
              
              // if(state.user.length > 0){
                
              //   if(state.user.role == "admin"){
              //     window.location.href = '/admin/sections';
              //   }
              //   if(state.user.role == "teacher"){
              //     window.location.href = '/teacher/subjects';
              //   }
              //   if(state.user.role == "student"){
              //     window.location.href = '/student/sections';
              //   }
              // }
        }catch(error){
            commit('SET_ERROR', true);
            commit('SET_MESSAGE', error.response.data.message )
            console.log(error)
        }
       
    },
    student_register: async ({commit, state, dispatch}, payload)=>{
      try {
        const result = await AuthService.student_register(payload)
        if(result.message == "Success"){
          commit('SET_TOKEN', result.data.token);
          commit('SET_AUTHENTICATED', true);
          commit('SET_ERROR', false);
          window.location.href = '/';
        }
      }catch(error){
        console.log(error);
        commit('SET_MESSAGE_BAG', error.response.data)
        commit('SET_ERROR', true);
        // commit('SET_MESSAGE', error.response.data.message )
      }
    },
    logout: async ({ commit,state}) => {
        try {
          const config = {
              token: state.authToken
          }
          const result = await AuthService.logout(config);
          commit('CLEAR_TOKEN');
          commit('SET_AUTHENTICATED', false);
          commit('SET_USER_DATA',[]);
          router.push('/login');
        } catch (error) {
          commit('SET_ERROR', true);
        }
        
      }
    ,
    clearSession: ({ commit }) => {
        commit('CLEAR_TOKEN');
      },
    GET_USER_PROFILE: async ({commit}) => {
      try{
          const result = await AuthService.getUserProfile()
          commit("SET_USER_DATA", result.data)
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