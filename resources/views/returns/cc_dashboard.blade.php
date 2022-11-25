@extends('bootstrap.layouts.layout')

@section('content')

<div id="dashboard">
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-list-ol"></i> Total Orders:<strong> {{ $totalRecords }}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-check"></i> Orders Done: <strong> {{ isset($counters[2]) ? $counters[2]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-plus"></i> New Orders: <strong>{{ isset($counters[0]) ? $counters[0]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-table-list"></i> In Process: <strong>{{ isset($counters[1]) ? $counters[1]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="mt-4">Daily Summary - @{{ filterDate }}</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="m-1">
                            <label for="dateFilter" class="form-label">Filter by date</label>
                            <input type="date" class="form-control form-control-sm" id="dateFilter" v-model="filterDate">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="dailyPNStatusChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>    
                                    <th scope="col">Store</th>
                                    <th scope="col">Good</th>
                                    <th scope="col">Bad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in dailyPNStatus" :key="index">
                                    <th scope="row">@{{ index + 1 }}</th>
                                    <td>@{{item.name}}</td>
                                    <td>@{{item.good}}</td>
                                    <td>@{{item.bad}}</td>
                                </tr>
                                <tr>
                                    <th scope="row"></th>
                                    <td>Total</td>
                                    <td>@{{totalGoodOrders}}</td>
                                    <td>@{{totalBadOrders}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col">
                                <canvas id="dailyStoresChart" height="150"></canvas>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Store</th>
                                            <th scope="col">Total Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in dailyStores" :key="index">
                                            <th scope="row">@{{ index + 1 }}</th>
                                            <td>@{{item.name}}</td>
                                            <td>@{{item.totalQuantity}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col">
                                <canvas id="myChart2" height="150"></canvas>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Total Registered</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in daily" :key="index">
                                            <th scope="row">@{{ index + 1 }}</th>
                                            <td>@{{item.complete_name}}</td>
                                            <td>@{{item.totalQuantity}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="card">
            <div class="card-header">
                <h4>General User Summary</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="myChart" height="150"></canvas>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Total Registered</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in general" :key="index">
                                    <th scope="row">@{{ index + 1 }}</th>
                                    <td>@{{item.complete_name}}</td>
                                    <td>@{{item.totalQuantity}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="card">
            <div class="card-header">
                <h4>General Stores Summary</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Store</th>
                                    <th scope="col">Total Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in stores" :key="index">
                                    <th scope="row">@{{ index + 1 }}</th>
                                    <td>@{{item.name}}</td>
                                    <td>@{{item.totalQuantity}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <canvas id="storesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
    const app = new Vue({
        el: '#dashboard',
        data: {
            general: [],
            daily: [],
            stores: [],
            dailyStores: [],
            dailyPNStatus: [],
            filterDate: new Date().toLocaleDateString(),
            generalGraph: null,
            dailyGraph: null,
            storesGraph: null,
            dailyStoresGraph: null,
            dailyPNStatusGraph: null,
        },
        computed: {
            totalGoodOrders() {
                return this.dailyPNStatus.reduce((counter, store) => {
                    return counter + store.good;
                }, 0);
            },
            totalBadOrders() {
                return this.dailyPNStatus.reduce((counter, store) => {
                    return counter + store.bad;
                }, 0);
            },
        },
        methods: {
            initializeData() {
                let ins = this
                this.general = [];
                this.daily = [];
                this.stores = [];
                this.dailyStores = [];

                axios({
                        method: 'get',
                        url: 'api/jrzDashboard',
                        params: {
                            date: ins.filterDate
                        }
                    })
                    .then(function(response) {
                        ins.general = response.data.generalSummary;
                        ins.daily = response.data.dailySummary;
                        ins.stores = response.data.storeSummary;
                        ins.dailyStores = response.data.dailyStoreSummary;
                        ins.dailyPNStatus = response.data.dailyPNStatusSummary;
                        ins.initializeGraphics(response.data.generalSummary);
                        ins.initializeDailyGraphics(response.data.dailySummary);
                        ins.initializeStoresGraphics(response.data.storeSummary);
                        ins.initializeDailyStoresGraphics(response.data.dailyStoreSummary);
                        ins.initializeDailyPNStatusGraphics(response.data.dailyPNStatusSummary);
                    });
            },
            initializeGraphics(data) {
                const ctx = document.getElementById('myChart');
                this.generalGraph = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: 'Records per User',
                            data: data,
                            backgroundColor: 'rgba(0, 81, 251, 0.6)',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        parsing: {
                            xAxisKey: 'complete_name',
                            yAxisKey: 'totalQuantity'
                        }
                    }
                });
            },
            initializeDailyGraphics(data) {
                const ctx = document.getElementById('myChart2');
                this.dailyGraph = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: 'Records daily per User',
                            data: data,
                            backgroundColor: 'rgba(251, 118, 0, 0.42)',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        parsing: {
                            xAxisKey: 'complete_name',
                            yAxisKey: 'totalQuantity'
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'User Orders Summary',
                                font: {
                                    size: 16
                                }
                            }
                        }
                    }
                });
            },
            initializeStoresGraphics(data) {
                const ctx = document.getElementById('storesChart');
                this.storesGraph = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: 'Records per Store',
                            data: data,
                            backgroundColor: [
                                'rgba(255, 99, 132)',
                                'rgba(255, 159, 64)',
                                'rgba(255, 205, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(54, 162, 235)',
                                'rgba(153, 102, 255)',
                                'rgba(201, 203, 207)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        parsing: {
                            yAxisKey: 'name',
                            xAxisKey: 'totalQuantity'
                        }
                    }
                });
            },
            initializeDailyStoresGraphics(data) {
                const ctx = document.getElementById('dailyStoresChart');
                this.dailyStoresGraph = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: 'Daily records per Store',
                            data: data,
                            backgroundColor: [
                                'rgba(255, 99, 132)',
                                'rgba(255, 159, 64)',
                                'rgba(255, 205, 86)',
                                'rgba(75, 192, 192)',
                                'rgba(54, 162, 235)',
                                'rgba(153, 102, 255)',
                                'rgba(201, 203, 207)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        parsing: {
                            yAxisKey: 'name',
                            xAxisKey: 'totalQuantity'
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Store Orders Summary',
                                font: {
                                    size: 16
                                }
                            }
                        }
                    }
                });
            },
            initializeDailyPNStatusGraphics(data) {
                const ctx = document.getElementById('dailyPNStatusChart');
                this.dailyPNStatusGraph = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        datasets: [{
                            label: 'Good returns',
                            data: data,
                            index: 'good',
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgb(54, 162, 235)',
                            borderWidth: 1,
                            hoverOffset: 4
                        }, {
                            label: 'Bad returns',
                            data: data,
                            index: 'bad',
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgb(255, 99, 132)',
                            borderWidth: 1,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        parsing: {
                            xAxisKey: 'name',
                            yAxisKey: ['good', 'bad']
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: 'Return conditions per Store',
                                font: {
                                    size: 16
                                }
                            }
                        }
                    }
                });
            }
        },
        mounted() {
            this.initializeData();
        },
        watch: {
            filterDate: function() {
                if (this.filterDate != null) {
                    if (this.generalGraph != null)
                        this.generalGraph.destroy();
                    if (this.dailyGraph != null)
                        this.dailyGraph.destroy();
                    if (this.storesGraph != null)
                        this.storesGraph.destroy();
                    if (this.dailyStoresGraph != null)
                        this.dailyStoresGraph.destroy();
                    if (this.dailyPNStatusGraph != null)
                        this.dailyPNStatusGraph.destroy();
                    this.initializeData();
                }
            }
        }
    })
</script>
@endsection