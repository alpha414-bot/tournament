import ApiClient from '../lib/api';
import Auth from './Auth';
import User from './User';
import Subject from './Subject';
import ClassList from './ClassList';
import Teacher from './Teacher';
import Section from './Section';
import Student from './Student';
import SchoolYear from './SchoolYear';

let client = new ApiClient();
let http = client.getClient();

const AuthService = new Auth(client);
const UserService = new User(client);
const SubjectService = new Subject(client);
const ClassService = new ClassList(client);
const TeacherService = new Teacher(client);
const SectionService = new Section(client);
const StudentService = new Student(client);
const SchoolYearService = new SchoolYear(client);

export {
    http,
    AuthService,
    UserService,
    SubjectService,
    ClassService,
    TeacherService,
    SectionService,
    StudentService,
    SchoolYearService
}