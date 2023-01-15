export default {
    methods: {
        SET_LOCALSTORAGE_DATA(name, data){
            localStorage.setItem(name, JSON.stringify(data))
        }
    },
}