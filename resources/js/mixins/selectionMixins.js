export default {
    data(){
        return{
            gradeLevels:[
                '7',
                '8',
                '9',
                '10',
            ],
            sections:['1','2','3','4'],
            units:['2','3','4'],
            relationships:[
                {text: 'Parent', value: 'parent'},
                {text: 'Guardian', value: 'guardian'}
            ],
            daysSelection:['M','T','W','TH','F','S'],
            admissionTypes: [
                'Regular', 'Irregular', 'Transferee', 'Dropped'
            ],
            schoolYears:[
                'All',
                '2020-2021',
                '2021-2022'
            ]
        }
    }
}