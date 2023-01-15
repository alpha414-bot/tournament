import Vue from 'vue'
import VueRouter from 'vue-router'
import store from "../store";
import MainView from '../views/MainView.vue'
import Dashboard from '../components/Pages/Dashboard'
import Login from '../views/LoginView.vue'

import Class from '../components/Pages/Class'
import Sections from '../components/Pages/Sections'
import Subjects from '../components/Pages/Subjects'
import Teacher from '../components/Pages/Teacher'
import Student from '../components/Pages/Student'
import School from '../components/Pages/School'


Vue.use(VueRouter)

const config = {
    mode:'history',
    // base: process.env.MIX_API_BASE_URL,
    linkActiveClass: "active",
    routes: [
        {
            path:'/',
            component: MainView,
            name: 'main-view',
            meta: {
                requiresAuth: true,
            },
            children:[
                // Admin
                {
                    path:'/dashboard',
                    component: Dashboard,
                    name: 'dashboard',
                    meta: {
                        requiresAuth: true,
                      },
                },
                {
                    path:'/admin/class',
                    component: Class,
                    name: 'class',
                    meta: {
                      requiresAuth: true,
                    },
                },
                {
                  path:'/admin/class/:id/students',
                  component: () => import('../components/Pages/Class/details'),
                  name: 'class_students',
                  meta: {
                    requiresAuth: true,
                  },
                },
                {
                    path:'/admin/sections',
                    component: Sections,
                    name: 'sections',
                    meta: {
                      requiresAuth: true,
                    },
                },
                {
                    path:'/admin/sections/:id',
                    name:'section_details',
                    component: () => import('../components/Pages/Sections/details'),
                    meta:{
                      requiresAuth: true
                    }
                },
                {
                    path:'/admin/subjects',
                    component: Subjects,
                    name: 'subjects',
                    meta: {
                      requiresAuth: true,
                    },
                },
                {
                  path:'/admin/teachers',
                  component: Teacher,
                  name: 'teachers',
                  meta: {
                    requiresAuth: true,
                  },
                },
                {
                  path:'/admin/teachers/:id',
                  name:'teacher_details',
                  component: () => import('../components/Pages/Teacher/details'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/admin/school-year',
                  component: School,
                  name: 'school',
                  meta: {
                    requiresAuth: true,
                  }
                },
                {
                  path:'/admin/students',
                  component: Student,
                  name: 'student',
                  meta: {
                    requiresAuth: true,
                  },
                },
                {
                  path:'/admin/students/:id',
                  name:'student_details',
                  component: () => import('../components/Pages/Student/details'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/admin/profile',
                  name:'admin_profile',
                  component: () => import('../components/Pages/Profile'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                //Teacher
                {
                  path:'/teacher/classes',
                  name:'teacher_classes',
                  component: () => import('../components/Teachers/Class'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/teacher/classes/:id/students',
                  name:'teacher_classes_students',
                  component: () => import('../components/Teachers/Class/details'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/teacher/profile',
                  name:'teacher_profile',
                  component: () => import('../components/Teachers/Profile'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                // Student
                {
                  path:'/student/classes',
                  name:'student_classes',
                  component: () => import('../components/Students/Class'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/student/grades',
                  name:'student_classes_grades',
                  component: () => import('../components/Students/Grade'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                {
                  path:'/student/profile',
                  name:'student_profile',
                  component: () => import('../components/Students/Profile'),
                  meta:{
                    // title: 'Teacher Details',
                    requiresAuth: true
                  }
                },
                
                
            ]
        },
        {
            path:'/login',
            component: Login,
            name: 'login'
        }
    ]
}

const router = new VueRouter(config)

// Auth guard
router.beforeEach(async(to, from, next) => {
    // Check if route requirees authentication.
    // await UserService.getProfile();
    
    if (to.matched.some((record) => record.meta.requiresAuth)) {
      // If not authenticated redirect to login page.
      if (!store.state.auth.authToken) {
        return next({
          path: "/login",
          query: { redirect: to.fullPath },
        });
      }
      next();
    } else {
      next();
      
    }
  });

export default router