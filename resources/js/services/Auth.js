export default class Auth {
    constructor(apiClient) {
      this.http = apiClient;
    }
  
    login(email, password) {
      return this.http.post('/auth', { email, password });
    }

    student_register(payload){
      return this.http.post('/student/register', payload);
    }
  
    logout(config) {
      return this.http.post('/logout',config);
    }
    getUserProfile(){
      return this.http.get('/profile')
    }
  }