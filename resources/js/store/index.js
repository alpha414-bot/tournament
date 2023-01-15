import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import auth from './modules/auth'
import subject from './modules/subject'
import classList from './modules/classList'
import teacher from './modules/teacher'
import student from './modules/student'
import section from './modules/section'
import schoolyear from './modules/schoolyear'

Vue.use(Vuex)

export default new Vuex.Store({
    modules:{
        auth,
        user,
        subject,
        classList,
        teacher,
        section,
        student,
        schoolyear
    }
})