export default class ClassList {
    constructor(apiClient) {
      this.http = apiClient;
    }
  
    getClasses(config) {
      return this.http.get('/classes',config);
    }

    getClassStudents(config) {
      return this.http.get(`/classes/students`,config);
    }

    getClassStudents2(config) {
      return this.http.get(`/classes/students2`,config);
    }
    
    createClass(config){
      return this.http.post('/classes', config);
    }

    editClass(id, data){

      return this.http.post(`/classes/${id}`, data);
    }

    updateClassSchedule(id, data){
      return this.http.put(`/classes/${id}`, data);
    }
    
    addClassStudents(config){
      return this.http.post('/classes/students2', config);
    }

    removeClassStudent2(config){
      return this.http.delete(`/classes/students2`,config);
    }

    removeClassStudent(id){
      return this.http.delete(`/classes/students/${id}`);
    }
    
    updateStudentGrade(id, payload){
      return this.http.put(`/students/subjects/${id}`, payload);
    }

    //Teacher
    getTClasses(config){
      return this.http.get('/teacher/classes',config);
    }
    getTStudents(config){
      return this.http.get('/teacher/students',config);
    }

    //selections
    getSYSelection(){
      return this.http.get('/selections/school-years');
    }

    getGradeLevels(){
      return this.http.get('/selections/grade-levels');
    }

    getSectionsSelection(config){
      return this.http.get('/selections/sections', config);
    }

    getSubjectsSelection(config){
      return this.http.get('/selections/subjects', config);
    }

    getStudentsSelection(config){
      return this.http.get('/classes/students-selection', config);
    }

    getGraph(){
      return this.http.get('/classes/dashboard');
    }
  }