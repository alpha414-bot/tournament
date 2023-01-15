export default class User {
    constructor(apiClient){
      this.http = apiClient;
    }
    getUserProfile(){
      return this.http.get('/profile')
    }

    createUser(user){
      return this.http.post('/users', user)
    };

    updateUserById(id, payload) {
      return this.http.put(`/users/${id}`, payload)
    };
    //Teachers
    getTeachers(config){
      return this.http.get('/teachers',config);
    };

    getTeacher(id){
      return this.http.get(`/teachers/${id}`);
    }
    createUser(user){
      return this.http.post('/teachers', user)
    };

    updateTeacher(id, user){
      return this.http.post(`/teachers/${id}`, user);
    }
   
    // getUsers(config){
    //   return this.http.get('/user/',config);
    // };
    
    // getUserById(id){
    //   return this.http.get(`/user/${id}`)
    // };
    
    // deleteUserById (id){
    //   return this.http.post(`/user/delete/${id}`)
    // }
  }