export default class Section {
    constructor(apiClient) {
      this.http = apiClient;
    }
    getSection(config){
      return this.http.get('/sections', config)
    }
    getSections(config){
      return this.http.get('/sections', config)
    }
    addSection(payload){
      return this.http.post('/sections', payload)
    }
    editSection(id, payload){
      return this.http.put(`/sections/${id}`, payload)
    }

    removeSection(id){
      return this.http.delete(`/sections/${id}`);
    }
  }