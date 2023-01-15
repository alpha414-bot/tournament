<template>
    <v-card height="100%">
        <v-card-title class="title">
            <span>Dashboard</span>
        </v-card-title>
        <v-card-text class="mb-3" style="height=300px;position:relative;">
            <BarChart class="chart" :chart-data="computedChartData" :chart-options="chartOptions"/>
        </v-card-text>
    </v-card>
</template>

<script>
    import BarChart from './BarChart.vue'
    import {mapState, mapActions} from 'vuex'
    export default {
        components: {
            BarChart
        },
        methods: {  
            
        },
        computed: {
            ...mapState("classList",["classes","students","sy_selection","grade_level_selection","sections_selection","subjects_selection","graph"]),
            computedChartData(){
                return {
                    labels: [''],
                    datasets: this.graph?this.graph.dataset:{}
                }
            },
            chartOptions() {
                return {
                    responsive: false,
                    maintainAspectRatios: false,
                    legend: {
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            color: 'blue'
                        },
                        onHover: function(e) {
                            e.target.style.cursor = 'pointer'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        yAxes: [{
                            ticks: {
                                // min: this.graph ? Math.min(...this.graph.total_enrolled):0,
                                max: this.graph?Math.max(...this.graph.total_enrolled)+1:0,
                                stepSize: 1,
                                reverse: false,
                                beginAtZero: true
                            }
                        }]
                    },
                    tooltips: {
                        enabled: true,
                        callbacks: {
                            label: function(context){
                                return `Student enrolled in year ${this._data.datasets[context.datasetIndex].label}: ${context.yLabel} student(s)`;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Enrolled Students in years.'
                    },                        
                }
            },
        },
        methods:{
            ...mapActions("classList",["GET_GRAPH"])
        },
        mounted(){
            this.GET_GRAPH();
        }
    }
</script>

<style scoped>
.chart{
      cursor: crosshair;
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
  }
</style>