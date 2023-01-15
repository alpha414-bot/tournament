export default class User {
    constructor(apiClient){
      this.http = apiClient;
    }
    getTeachers(config){
      return this.http.get('/teachers',config);
    };

    getTeacher(id){
      return this.http.get(`/teachers/${id}`);
    }
    createTeacher(user){
      return this.http.post('/teachers', user)
    };

    updateTeacher(id, user){
      return this.http.post(`/teachers/${id}`, user);
    }

    getClasses(config, id){
      return this.http.get(`/teachers/${id}/classes`, config);
    }
    updateClass(id, data){
      return this.http.post(`/teachers/class/${id}`, data);
    }
    assignSubjects(payload){
      return this.http.post(`/teachers/subjects`, payload);
    }
    removeSubject(id){
      return this.http.put(`/teachers/subjects/${id}`);
    }
  }