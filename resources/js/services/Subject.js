export default class Subject {
    constructor(apiClient) {
      this.http = apiClient;
    }
  
    getSubjects(config) {
      return this.http.get('/subjects', config);
    }

    createSubject(config){
      return this.http.post('/subjects', config)
    }
    updateSubject({id,subject}){
      return this.http.post(`/subjects/${id}`, subject)
    }
  }