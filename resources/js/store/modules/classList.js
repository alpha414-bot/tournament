import {ClassService} from '../../services'


const state = {
    classes: [],
    class:[],
    students: [],
    sy_selection: [],
    grade_level_selection:[],
    sections_selection:[],
    subjects_selection:[],
    students_selection:[],
    graph:[],
    message: ''
}

const getters = {
    get_sy_selection: state => {

      let all = {
        key: "All",
        value: "all",
        selected: false
      }
      let sy = state.sy_selection.map( sy => {
        return {
          key: sy.key,
          value: sy.value,
          selected: false
        }
      })
      if(sy.length > 0){
        sy.unshift(all)
      }
      return sy;
    },
    get_grade_level_selection: state => {

      let all = {
        key: "All",
        value: "all",
        selected: false
      }
      let grade_level = state.grade_level_selection.map( grade_level => {
        return {
          key: grade_level.key,
          value: grade_level.value,
          selected: false
        }
      })
      if(grade_level.length > 0){
       grade_level.unshift(all)
      }
      return grade_level;
    },
    get_sections_selection: state => {

      let all = {
        key: "All",
        value: "all",
        selected: false
      }
      let sections = state.sections_selection.map( sections => {
        return {
          key: sections.key,
          value: sections.value,
          selected: false
        }
      })
      if(sections.length > 0){
        sections.unshift(all)
      }
      return sections;
    },
    
}

const mutations = {
    SET_CLASSES: (state,payload) => state.classes = payload,
    SET_NEW_CLASSES: (state, payload) => {
      payload.map((subject)=>{
        const index = state.classes.findIndex(subjectclass => subjectclass.id === subject.id)
        if(index === -1){
          state.classes.unshift(subject)
        }
      })
    },
    SET_UPDATED_CLASS: (state, payload) =>{
      const index = state.classes.findIndex(subjectClass => subjectClass.id === payload.id);
      state.classes.splice(index, 1, payload)
    },
    SET_STUDENTS:     (state,payload) => state.students = payload,
    SET_NEW_STUDENTS: (state, payload) =>{
      payload.map((student)=>{
          state.students.unshift(student)
      })
    },
    REMOVE_STUDENT: (state, id) => {
      state.students = state.students.filter(student => student.id !== id)
    },
    SET_UPDATED_STUDENT_GRADE: (state, payload) =>{
      const index = state.students.findIndex(student => student.id === payload.id);
      state.students.splice(index, 1, payload)
    },
    SET_SY_SELECTION: (state,payload) => state.sy_selection = payload,
    SET_GL_SELECTION: (state,payload) => state.grade_level_selection = payload,
    SET_SECTIONS_SELECTION: (state,payload) => state.sections_selection = payload,
    SET_SUBJECTS_SELECTION: (state,payload) => state.subjects_selection = payload,
    SET_STUDENTS_SELECTION: (state,payload) => state.students_selection = payload,
    SET_GRAPH: (state,payload) => state.graph = payload,
    SET_MESSAGE: (state,message) => state.message = message,
}

const actions = {
    GET_CLASSES: async ({commit}, payload) => {
        try{
            var config ={
                params: payload
            }
            const result = await ClassService.getClasses(config)
            commit("SET_CLASSES", result.data)
        }catch(error){
          console.log(error)
        }
      },
    GET_CLASS_STUDENTS: async ({commit}, payload) => {
      try{
          var config ={
              params: payload
          }
          const result = await ClassService.getClassStudents(config)
          commit("SET_STUDENTS", result.data)
      }catch(error){
        console.log(error)
      }
    },
    GET_CLASS_STUDENTS2: async ({commit}, payload) => {
      try{
          var config ={
              params: payload
          }
          const result = await ClassService.getClassStudents2(config)
          commit("SET_STUDENTS", result.data)
      }catch(error){
        console.log(error)
      }
    },
    EDIT_CLASS_SCHEDULE: async ({commit},payload) => {
        commit('SET_MESSAGE', '')
        try{
            const {id , data} = payload
            const result = await ClassService.updateClassSchedule(id,data)
            commit('SET_MESSAGE', 'Schedule updated successfully.')
            commit("SET_UPDATED_CLASS", result.data)
        }catch(error){
            console.log(error)
            let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
            commit('SET_MESSAGE', message)
        }
    },
    ADD_CLASS: async ({commit}, payload) =>{
        try{
            var config ={
                params: payload
            }
            const result = await ClassService.createClass(payload)
            commit("SET_NEW_CLASSES", result.data)
        }catch(error){
          let message = error.response.data.message? error.response.data.message : Object.values(error.response.data)[0].toString()
          commit("SET_MESSAGE", message)
        }
    },
    ADD_CLASS_STUDENTS: async ({commit}, payload) =>{
      try{
          var config ={
              params: payload
          }
          const result = await ClassService.addClassStudents(payload)
          console.log(result.data)
          // commit("SET_NEW_STUDENTS", result.data)
      }catch(error){
        console.log(error)
      }
    },
    REMOVE_CLASS_STUDENT: async ({commit}, id) =>{
      try{
          await ClassService.removeClassStudent(id)
          commit("REMOVE_STUDENT", id)
      }catch(error){
        console.log(error)
      }
    },
    REMOVE_CLASS_STUDENT2: async ({commit}, payload) =>{
      try{
          var config ={
            params: payload
          }
          const result = await ClassService.removeClassStudent2(config)
          commit("REMOVE_STUDENT", payload.id)
      }catch(error){
        console.log(error)
      }
    },

    UPDATE_STUDENT_GRADE: async ({commit}, payload) =>{
      try{
          
          const result = await ClassService.updateStudentGrade(payload.id, payload)
          console.log(result)
          commit("SET_UPDATED_STUDENT_GRADE", result.data)
      }catch(error){
        console.log(error)
      }
    },

    //Teacher
      GET_SY_SELECTION: async ({commit}) => {
        try{
            const result = await ClassService.getSYSelection()
            commit("SET_SY_SELECTION", result.data)
        }catch(error){
          console.log(error)
        }
      },
      GET_GRADE_LEVEL_SELECTION: async ({commit}) => {
        try{
            const result = await ClassService.getGradeLevels()
            commit("SET_GL_SELECTION", result.data)
        }catch(error){
          console.log(error)
        }
      },
      GET_SECTIONS_SELECTION: async ({commit}, payload) => {
        try{
            let config={
              params: payload
            }
            const result = await ClassService.getSectionsSelection(config)
            commit("SET_SECTIONS_SELECTION", result.data)
        }catch(error){
          console.log(error)
        }
      },
      GET_SUBJECTS_SELECTION: async({commit}, payload) => {
        try{
          let config = {
            params: payload
          }
          const result = await ClassService.getSubjectsSelection(config)
          commit("SET_SUBJECTS_SELECTION", result.data);
        }catch(error){
          console.log(error)
        } 
      },
      GET_STUDENTS_SELECTION: async({commit}, payload) => {
        try{
          let config = {
            params: payload
          }
          const result = await ClassService.getStudentsSelection(config)
          commit("SET_STUDENTS_SELECTION", result.data);
        }catch(error){
          console.log(error)
        } 
      },
      GET_GRAPH: async({commit}, payload) => {
        try{
          const result = await ClassService.getGraph()
          let array, arrayOfObj, sortedArrayOfObj, carrierArray = [];
          array = result.data;
          arrayOfObj = array['school_year'].map(function(d, i) {
              return {
                  label: Number(d),
                  data: array['total_enrolled'][i] || 0
              };
          });
          
          sortedArrayOfObj = arrayOfObj.sort(function(a, b) {
              return a.label-b.label;
          })
          var newArrayLabel = [];
          var newArrayData = [];
          var backgroundColor =  []; var borderColor = [];
          var value = ''; var valueS= ''; var valueT = '';
          
          sortedArrayOfObj.forEach(function(d){
            newArrayLabel.push(d.label);
            newArrayData.push(d.data);
          });
          var datasetObjects = []
          newArrayLabel.forEach(function(d, index){
            value = Math.round(Math.random()*255); valueS = Math.round(Math.random()*255); valueT = Math.round(Math.random()*255);
            datasetObjects.push({
                label: d,                        
                barPercentage: 0.5,
                fill: false, 
                backgroundColor: `rgba(${value}, ${valueS}, ${valueT}, 0.2)`,
                borderColor: `rgba(${value}, ${valueS}, ${valueT})`,
                borderWidth: 1,
                data: [newArrayData[index]],
                tension:0,
                pointRadius: 2,
            })
          })
          carrierArray = {school_year:newArrayLabel, total_enrolled:newArrayData, dataset:datasetObjects}
          commit("SET_GRAPH", carrierArray);
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