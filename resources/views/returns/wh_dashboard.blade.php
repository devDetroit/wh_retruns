@extends('bootstrap.layouts.layout')

@section('content')

<div id="dashboard">
    <div class="row mt-4">
        <div class="col-md-6">
            <h4 class="m-4">General Summary</h4>
            <canvas id="myChart" height="105"></canvas>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="m-4">Daily Summary - @{{ filterDate }}</h5>

                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dateFilter" class="form-label">Filter by date</label>
                        <input type="date" class="form-control form-control-sm" id="dateFilter" v-model="filterDate">
                    </div>
                </div>
            </div>
            <canvas id="myChart2" height="105"></canvas>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Total Registred</th>
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
        <div class="col-md-6">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Total Registred</th>
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

@endsection


@section('scripts')
<script>
    const app = new Vue({
        el: '#dashboard',
        data: {
            general: [],
            daily: [],
            filterDate: new Date().toLocaleDateString(),
            generalGraph: null,
            dailyGraph: null,
        },
        methods: {
            initializeData() {
                let ins = this
                this.general = [];
                this.daily = [];

                axios({
                        method: 'get',
                        url: 'api/dashboard',
                        params: {
                            date: ins.filterDate
                        }
                    })
                    .then(function(response) {
                        ins.general = response.data.generalSummary;
                        ins.daily = response.data.dailySummary;
                        ins.initializeGraphics(response.data.generalSummary);
                        ins.initializeDailyGraphics(response.data.dailySummary);
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
                            backgroundColor: 'rgba(82, 102, 255, 0.8)'
                        }, ]
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
                            backgroundColor: 'rgba(255, 69, 69, 0.8)'
                        }, ]
                    },
                    options: {
                        parsing: {
                            xAxisKey: 'complete_name',
                            yAxisKey: 'totalQuantity'
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
                    this.initializeData();
                }
            }
        }
    })
</script>
@endsection