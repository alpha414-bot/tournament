export default class SchoolYear {
    constructor(apiClient){
        this.http = apiClient;
    }
    getSchoolYear(config){
        return this.http.get('/school-year', config);
    }

    createSchoolYear(year){
        return this.http.post('/school-year', year);
    }

    updateSchoolYear({id, data}){
        return this.http.post(`/school-year/${id}`, data)
    }

    removeSchoolYear({id}){
        return this.http.delete(`/school-year/${id}`, {})
    }
}