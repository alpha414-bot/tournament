<script>
    import { Bar, mixins } from 'vue-chartjs'
    const { reactiveProp } = mixins

    export default {
        extends: Bar,
        mixins: [reactiveProp],
        props: {
            chartData: {
                type: Object,
                required: true,
            },
            chartOptions: {
                type: Object,
                required: true,
            }
        },
        methods: {
            initialize(){
                this.renderChart(this.chartData, this.chartOptions)
            }
        },
        mounted(){
            this.initialize();
        },
    }
</script>