export default class User {
    constructor(apiClient){
      this.http = apiClient;
    }
    getStudents(config){
      return this.http.get('/students',config);
    };

    getStudent(id){
      return this.http.get(`/students/${id}`);
    }
    createStudent(user){
      return this.http.post('/students', user)
    }

    updateStudent(id, user){
      return this.http.post(`/students/${id}`, user);
    }

    getStudentsSelection(config){
      return this.http.get('/students/grade-level', config);
    }

    getEnrollments(id, config){
      return this.http.get(`/students/enrollments/${id}`, config);
    }

    enrollStudent(payload){
      return this.http.post(`/students/enrollments`, payload);
    }

    getSubjects(config){
      return this.http.get(`/students/subjects`,config);
    }

    getGrades(config){
      return this.http.get(`/students/subjects/grades`,config);
    }

    getSubjectsSelection(config){
      return this.http.get(`/students/subjects/subjects-selections`,config);
    }

    assignSubjects(config){
      return this.http.post(`/students/subjects`,config.params);
    }

    removeSubject(id){
      return this.http.delete(`/students/subjects/${id}`);
    }

    // updateClass(id, data){
    //   return this.http.post(`/students/class/${id}`, data);
    // }
  }