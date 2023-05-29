@extends('bootstrap.layouts.layout')

@section('styles')
<style>
    .cardCircle {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background-color: #ffffff;
        margin: 0 20px;
        width: 100%;
        height: 550px;
        border-radius: 5px;
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.2);
    }

    .cardCircle .percent {
        position: relative;
    }

    .cardCircle svg {
        position: relative;
        width: 410px;
        height: 410px;
        transform: rotate(-90deg);
    }

    .cardCircle svg circle {
        width: 100%;
        height: 100%;
        fill: none;
        stroke: #f0f0f0;
        stroke-width: 10;
        stroke-linecap: round;
    }

    .cardCircle svg circle:last-of-type {
        stroke-dasharray: 1130px;
        stroke-dashoffset: calc(1130px - (1130px * var(--percent)) / 100);
        stroke: #3498db;
    }

    .cardCircle .number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .cardCircle .number h3 {
        font-weight: 500;
        font-size: 4rem;
    }

    .cardCircle .number h3 span {
        font-size: 3rem;
    }

    .cardCircle .title h2 {
        margin: 25px 0 0;
    }

    .card:nth-child(1) svg circle:last-of-type {
        stroke: #f39c12;
    }

    .cardCircle:nth-child(2) svg circle:last-of-type {
        stroke: #2ecc71;
    }
</style>
<style>
    .cardOriginal {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        background-color: #ffffff;
        margin: 0 20px;
        width: 480px;
        height: 350px;
        border-radius: 5px;
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.2);
    }

    .cardOriginal .percent {
        position: relative;
    }

    .cardOriginal svg {
        position: relative;
        width: 210px;
        height: 210px;
        transform: rotate(-90deg);
    }

    .cardOriginal svg circle {
        width: 100%;
        height: 100%;
        fill: none;
        stroke: #f0f0f0;
        stroke-width: 10;
        stroke-linecap: round;
    }

    .cardOriginal svg circle:last-of-type {
        stroke-dasharray: 625px;
        stroke-dashoffset: calc(625px - (625px * var(--percent)) / 100);
        stroke: #3498db;
    }

    .cardOriginal .number {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .cardOriginal .number h3 {
        font-weight: 200;
        font-size: 3.5rem;
    }

    .cardOriginal .number h3 span {
        font-size: 2rem;
    }

    .cardOriginal .title h2 {
        margin: 25px 0 0;
    }

    .cardOriginal:nth-child(1) svg circle:last-of-type {
        stroke: #f39c12;
    }

    .cardOriginal:nth-child(2) svg circle:last-of-type {
        stroke: #2ecc71;
    }
</style>
@endsection

@section('content')

<div id="printLabelApp">
    <div class="row justify-content-center" style="background-color: #f6f6f6;">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-6 border-end" style="margin-top: 5rem !important;">
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="col-md-12 d-flex justify-content-center">
                                <p style="font-size: 3em;  color: black; font-weight: bold;"> @{{line}}</p>
                            </div>
                        </div>

                        <div class="row  justify-content-center">
                            <div class="col-md-12 d-flex justify-content-center">
                                @verbatim
                                <div class="cardCircle border border-secondary">
                                    <div class="percent">
                                        <svg>
                                            <circle cx="205" cy="205" r="180"></circle>
                                            <circle cx="205" cy="205" r="180" :style='"--percent:" + total.porcentaje'></circle>
                                        </svg>
                                        <div class="number">
                                            <h3>{{total.porcentaje}}<span>%</span></h3>
                                        </div>
                                    </div>
                                    <div class="title" style="width: 100%;">
                                        <table class="table table-borderless text-center" style="font-size: 2.7em;">
                                            <thead>
                                                <th>Actual</th>
                                                <th>Total</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{total.actuales}}</td>
                                                    <td>{{ total.total }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endverbatim
                            </div>
                        </div>
                        <div class="row mt-4 justify-content-center">
                            <div class="col-md-12 d-flex justify-content-center">
                                <h4> ultima Actualizacion : @{{ updateDate }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <template v-if="validateIfCounterIsNotNull">
                        <div>
                            <div class="row justify-content-center" v-for="(item, index) in counters.target " :key="index">
                                <div class="col-md-6">

                                    <div class="row justify-content-center">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <p style="font-size: 2em;  color: black; font-weight: bold;">@{{ counters.target[index]['station'] }}</p>
                                        </div>
                                        @verbatim
                                        <div class="cardOriginal border border-secondary shadow p-1 bg-body rounded">
                                            <div class="percent">
                                                <svg>
                                                    <circle cx="105" cy="105" r="100"></circle>
                                                    <circle cx="105" cy="105" r="100" :style="'--percent:' + counters.target[index]['Porcent']"></circle>
                                                </svg>
                                                <div class="number">
                                                    <h3>{{ counters.target[index]['Porcent'] }}<span>%</span></h3>
                                                </div>
                                            </div>
                                            <div class="title" style="width: 100%;">
                                                <table class="table table-borderless text-center" style="font-size: 1.8em;">
                                                    <thead>
                                                        <th>Actual</th>
                                                        <th>Total</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ counters.target[index]['TotalScanned'] ?? 0}}</td>
                                                            <td>{{ counters.target[index]['goal'] }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endverbatim
                                    </div>

                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    let getCUrrentTime = function() {
        var date = new Date();
        app.updateDate = date.toLocaleString();
    }

    const app = new Vue({
        el: '#printLabelApp',
        data: {
            warehouse: 'jrz',
            totalPZS: 0,
            totalScanned: 0,
            url: new URLSearchParams(location.search),
            line: new URLSearchParams(location.search).get('line'),
            porcentaje: 0,
            counters: null,
            total: {
                total: 0,
                actuales: 0,
                porcentaje: 0
            },
            updateDate: null
        },
        methods: {
            getWarehouse(warehouse) {
                this.warehouse = warehouse;
                closeModalButton.click();
                //this.getData()
            },
            getData() {
                let currentInstance = this
                this.clearFields();
                axios({
                        method: 'get',
                        url: '/labels/actual',
                        params: {
                            line: currentInstance.url.get('line'),
                            warehouse: currentInstance.warehouse,
                        }
                    })
                    .then(function(response) {
                        currentInstance.initializeCounters(response.data)
                        getCUrrentTime();
                    }).catch(error => {
                        sweetAlertAutoClose('error', "Error procesando la informacion")
                        console.error(error)
                    });
            },
            clearFields() {
                this.total.total = 0
                this.total.actuales = 0
                this.total.porcentaje = 0
                this.counters = null;
            },
            initializeCounters(data) {
                this.counters = data
                for (let index = 0; index < data.target.length; index++) {
                    this.counters.target[index]['stationID'] = data.target[index]['station'];
                    this.counters.target[index]['TotalScanned'] = data.target[index]['total_printed']
                    var totalScanned = this.counters.target[index]['TotalScanned'];
                    this.counters.target[index]['Porcent'] = Math.round((totalScanned / this.counters.target[index]['goal']) * 100);
                    this.total.total += this.counters.target[index]['goal'];
                    this.total.actuales += this.counters.target[index]['TotalScanned'];
                }
                this.total.porcentaje = Math.round((this.total.actuales / this.total.total) * 100)
            }
        },
        mounted() {
            //locationButton.click();
            const urlParams = new URLSearchParams(location.search);
            this.getData();
            var date = new Date();
            this.updateDate = date.toLocaleString();

            setInterval(() => {
                this.getData();
            }, 200000)

        },
        computed: {
            getPorcent: function() {
                return (this.totalScanned / this.totalPZS) * 100;
            },
            validateIfCounterIsNotNull: function() {
                return this.counters != null;
            },
            warehouseDescription: function() {
                var description = ''
                switch (this.warehouse) {
                    case 'jrz':
                        description = 'REMAN'
                        break;

                    case 'elp':
                        description = 'ELP WH'
                        break;

                    default:
                        description = ''
                        break;
                }
                return description;
            },
        }
    })
</script>


@endsection