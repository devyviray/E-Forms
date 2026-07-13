<template>
 <div class="dashboard-cards mt-4 mb-4">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="fs-1 mb-3 text-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a 
                        :href="viewDrdrs"
                        target="_blank"
                        class="text-xs font-weight-bold gradient-text text-uppercase mb-3 text-shadow-hover text-decoration-none"
                        style="min-height: 50px; display: flex; align-items: center; cursor: pointer;"
                        >
                        Document Review & Distribution Request
                        </a>
                        <p class="fs-2 fw-bold mb-2 mt-0">{{ drdrs }}</p>
                        <p class="small text-muted mb-0">Total Entries</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="fs-1 mb-3 text-success">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a 
                        :href="viewDdrs"
                        target="_blank"
                        class="text-xs font-weight-bold gradient-text text-uppercase mb-3 text-shadow-hover text-decoration-none"
                        style="min-height: 50px; display: flex; align-items: center; cursor: pointer;"
                        >
                        Document Distribution
                        </a>
                        <p class="fs-2 fw-bold mb-2 mt-0">{{ ddrs }}</p>
                        <p class="small text-muted mb-0">Total Entries</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="fs-1 mb-3 text-info">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a 
                        :href="viewNcns"
                        target="_blank"
                        class="text-xs font-weight-bold gradient-text text-uppercase mb-3 text-shadow-hover text-decoration-none"
                        style="min-height: 50px; display: flex; align-items: center; cursor: pointer;"
                        >
                        Non-Conformance Notification
                        </a>
                        <p class="fs-2 fw-bold mb-2 mt-0">{{ ncns }}</p>
                        <p class="small text-muted mb-0">Total Entries</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="fs-1 mb-3 text-danger">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <a 
                        :href="viewCcirs"
                        target="_blank"
                        class="text-xs font-weight-bold gradient-text text-uppercase mb-3 text-shadow-hover text-decoration-none"
                        style="min-height: 50px; display: flex; align-items: center; cursor: pointer;"
                        >
                        Customer Complaint Investigation
                        </a>
                        <p class="fs-2 fw-bold mb-2 mt-0">{{ ccirs }}</p>
                        <p class="small text-muted mb-0">Total Entries</p>
                    </div>
                </div>
            </div>

            <!-- Yearly Count Card -->
            <div class="col-md-8 mt-5">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                    <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-xs font-weight-bold gradient-text text-uppercase mb-0 text-shadow-hover text-decoration-none">Yearly Entries</h5>
                            <div class="d-flex align-items-center gap-2">
                                <select 
                                    v-model.number="selectedYear" 
                                    @change="updateYearlyChart" 
                                    class="form-control form-control-sm rounded-2" 
                                    style="width: 120px; height: 38px; border: 1px solid #e3e6f0; font-size: 14px; font-weight: 500;"
                                >
                                    <option v-for="year in years" :key="year" :value="year">
                                        {{ year }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div id="yearlyChart" style="height: 300px; position: relative;">
                            <canvas ref="yearlyChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Count Card -->
            <div class="col-md-4 mt-5">
                <div class="card h-100 border-0 shadow-sm rounded-3 position-relative overflow-hidden">
                <div class="position-absolute top-0 start-0 end-0" style="height: 4px;"></div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-xs font-weight-bold gradient-text text-uppercase mb-0 text-shadow-hover">Daily Entries</h5>
                    <div class="fs-2 text-success">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    </div>
                    <div style="height: 300px; position: relative;">
                        <canvas ref="dailyChartCanvas"></canvas>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js';

export default {
    data(){
        return{
            drdrs: '',
            ddrs: '',
            ccirs: '',
            ncns: '',
            errors: '',
            dailyChart: null,
            yearlyChart: null,
            dailyData: {},
            yearlyData: {},
            selectedYear: new Date().getFullYear(),
            years: [],
         }
    },
    mounted() {
        this.fetchYearDropdown();
        this.fetchDashboardData();
        this.fetchDashboardDataDaily();
    },
    created(){
    },
   
    methods: {  
        fetchDashboardData(){
            axios.get('/admin/dashboard-data')
                .then(response => {
                    this.drdrs = response.data.drdr;
                    this.ddrs = response.data.ddr;
                    this.ccirs = response.data.ccirs;
                    this.ncns = response.data.ncns;
                })
                .catch(error => {
                    this.errors = error.response.data.errors || 'Error fetching data';
                });  
        },
        fetchDashboardDataDaily(){
            axios.get('/admin/dashboard-data-daily')
                .then(response => {
                    this.dailyData = response.data;
                    this.renderDailyChart(this.dailyData);
                })
                .catch(error => {
                    this.errors = error.response.data.errors || 'Error fetching data';
                });  
        },
        fetchDashboardDataPerYear(year = this.selectedYear){
            axios.get(`/admin/dashboard-data-per-year/${year}`)
                .then(response => {
                    this.yearlyData = response.data;
                    this.renderYearlyChart(this.yearlyData);
                })
                .catch(error => {
                    this.errors = error.response.data.errors || 'Error fetching data';
                });  
        },
        fetchYearDropdown() {
            axios.get('/admin/dashboard-year-dropdown')
                .then(response => {
                    this.years = response.data.years;
                    
                    const currentYear = new Date().getFullYear();
                    if (this.years.includes(currentYear)) {
                        this.selectedYear = currentYear;
                    } else {
                        this.selectedYear = Math.max(...this.years);
                    }
                    
                    this.$nextTick(() => {
                        this.fetchDashboardDataPerYear(this.selectedYear);
                    });
                })
                .catch(error => {
                    this.errors = error.response.data.errors || 'Error fetching data';
                });
        },
        updateYearlyChart() {
            this.fetchDashboardDataPerYear(this.selectedYear);
        },
        renderCharts() {
            this.renderDailyChart(this.dailyData);
            this.renderYearlyChart(this.yearlyData);
        },
        renderDailyChart(dailyData = null) {
            if (!this.$refs.dailyChartCanvas) return;
            
            const ctx = this.$refs.dailyChartCanvas.getContext('2d');

            if (this.dailyChart) {
                this.dailyChart.destroy();
            }

            const counts = dailyData.counts || {
                DRDR: 0,
                DDR: 0,
                NCN: 0,
                CCIR: 0
            };

            this.dailyChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['DRDR', 'DDR', 'NCN', 'CCIR'],
                    datasets: [
                        {
                            data: [counts.DRDR, counts.DDR, counts.NCN, counts.CCIR],
                            backgroundColor: [
                                '#1abc9c',
                                '#16a085',
                                '#27ae60',
                                '#2ecc71'
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        labels: {
                            fontSize: 12,
                            fontStyle: '600',
                            fontColor: '#565656',
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFontSize: 14,
                        bodyFontSize: 13,
                        borderColor: '#1abc9c',
                        borderWidth: 1,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.value + ' entries';
                            }
                        }
                    }
                }
            });
        },
        renderYearlyChart(yearlyData = null) {
            if (!this.$refs.yearlyChartCanvas) return;
            
            const ctx = this.$refs.yearlyChartCanvas.getContext('2d');

            if (this.yearlyChart) {
                this.yearlyChart.destroy();
            }

            const labels = yearlyData.months || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const counts = yearlyData.counts || {
                DRDR: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                DDR: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                NCN: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                CCIR: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            };

            this.yearlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'DRDR',
                            data: counts.DRDR,
                            backgroundColor: '#1abc9c'
                        },
                        {
                            label: 'DDR',
                            data: counts.DDR,
                            backgroundColor: '#16a085'
                        },
                        {
                            label: 'NCN',
                            data: counts.NCN,
                            backgroundColor: '#27ae60'
                        },
                        {
                            label: 'CCIR',
                            data: counts.CCIR,
                            backgroundColor: '#2ecc71'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        labels: {
                            fontSize: 12,
                            fontStyle: '600',
                            fontColor: '#565656',
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                fontColor: '#565656'
                            },
                            gridLines: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                fontColor: '#565656'
                            }
                        }]
                    }
                }
            });
        }
    }, 
    // links for the cards to redirect to the respective pages
    computed:{
        viewDrdrs() {
            return window.location.origin + '/admin/drdrs';
        },
        viewDdrs() {
            return window.location.origin + '/admin/ddrs';
        },
        viewNcns() {
            return window.location.origin + '/admin/ncns';
        },
        viewCcirs() {
            return window.location.origin + '/admin/ccirs';
        }
    }
}
</script>

<style scoped>
.gradient-text {
    background: linear-gradient(90deg, rgba(42, 123, 155, 1) 0%, rgba(55, 145, 149, 1) 0%, rgba(87, 199, 133, 1) 100%, rgba(237, 221, 83, 1) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.text-shadow-hover {
  transition: all 0.3s ease;
  text-shadow: none;
}

.text-shadow-hover:hover {
  text-shadow: 0 2px 8px rgba(26, 188, 156, 0.4);
}

.card {
    border-radius: 15px !important;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }
}
</style>