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
        width: 480px;
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
        font-size: 3.5rem;
    }

    .cardCircle .number h3 span {
        font-size: 2rem;
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
@endsection

@section('content')

<div id="printLabelApp">

    <div class="row mt-1 justify-content-center border-bottom">
        <div class="col-md-6 d-flex justify-content-center">
            <p style="font-size: 3em;  color: black; font-weight: bold;">@{{url.get('line')}}</p>
        </div>
    </div>

    <div class="row mt-1 justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            @verbatim
            <div class="cardCircle">
                <div class="percent">
                    <svg>
                        <circle cx="205" cy="205" r="180"></circle>
                        <circle cx="205" cy="205" r="180" :style='"--percent:" + getPorcent'></circle>
                    </svg>
                    <div class="number">
                        <h3>{{getPorcent}}<span>%</span></h3>
                    </div>
                </div>
            </div>
            @endverbatim

        </div>
    </div>
    <div class="row  mt-2 justify-content-center">
        <div class="col-md-6 shadow p-1 bg-body rounded">
            <table class="table table-borderless table-sm text-center">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">
                            <p style="font-size: 3.5em;  color:#307bbb;">ACTUAL</p>
                        </th>
                        <th scope="col">
                            <p style="font-size: 3.5em; color:green;">TOTAL</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p style="font-size: 3.2em; color:#307bbb; font-weight: bold;">@{{totalScanned}}</p>
                        </td>
                        <td>
                            <p style="font-size: 3.2em; color:green; font-weight: bold;">@{{totalPZS}}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <x-location-labels></x-location-labels>

</div>
@endsection



@section('scripts')
<script>
    const app = new Vue({
        el: '#printLabelApp',
        data: {
            warehouse: '',
            totalPZS: 0,
            totalScanned: 0,
            url: new URLSearchParams(location.search),
            porcentaje: 0
        },
        methods: {
            getWarehouse(warehouse) {
                this.warehouse = warehouse;
                closeModalButton.click();
                this.getData()
            },
            getData() {
                let currentInstance = this
                axios({
                        method: 'get',
                        url: '/labels/actual',
                        params: {
                            line: currentInstance.url.get('line'),
                            warehouse: currentInstance.warehouse,
                        }
                    })
                    .then(function(response) {
                        currentInstance.totalPZS = response.data.target.length > 0 ? response.data.target[0].goal : 0;
                        currentInstance.totalScanned = response.data.totalScanned.length > 0 ? response.data.totalScanned[0].total_labels_scanned : 0;
                    }).catch(error => {
                        sweetAlertAutoClose('error', "Error procesando la informacion")
                        console.error(error)
                    });
            }
        },
        mounted() {
            locationButton.click();
            const urlParams = new URLSearchParams(location.search);
        },
        computed: {
            getPorcent: function() {
                return (this.totalScanned / this.totalPZS) * 100;
            }
        }
    })
</script>


@endsection