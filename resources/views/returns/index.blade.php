@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row mt-2">
        <div class="col-md-12 text-end">
            <strong>Last Updated At:</strong> @{{ updateDate }}
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-list-ol"></i> Total records:<strong> {{ $totalRecords }}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-check"></i> Total Done: <strong> {{ isset($counters[2]) ? $counters[2]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-plus"></i> Total new: <strong>{{ isset($counters[0]) ? $counters[0]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-table-list"></i> In process: <strong>{{ isset($counters[1]) ? $counters[1]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="btnUserType" value="{{ Auth::user()->user_type }}">
    <div class="row mt-4">
        <div class="col-md-12">
            <div id="returns-table"></div>
        </div>
    </div>
</div>
@endsection



@section('scripts')
@if (session('status'))
<script>
    let timerInterval;
    let message = '<?= session('status') ?>';
    Swal.fire({
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 1500,
        willClose: () => {
            clearInterval(timerInterval)
        }
    });
</script>
@endif

<script>
    var viewBtn = function(cell, formatterParams, onRendered) { //plain text value
        return '<button type="button" class="btn btn-sm btn-primary">Details</button>';
    };

    var deleteBtn = function(cell, formatterParams, onRendered) { //plain text value
        return '<button type="button" class="btn btn-sm btn-danger">Delete</button>';
    };
    let getCUrrentTime = function() {
        var date = new Date();
        app.updateDate = date.toLocaleString();
    }
    const app = new Vue({
        el: '#indexapp',
        data: {
            table: null,
            trackNumber: '',
            status: '',
            updateDate: null
        },
        methods: {
            onDelete(data) {
                let deleteConfirmation = confirm('are you sure to delete this record?');

                if (!deleteConfirmation)
                    return;

                let inst = this;

                axios.post(`/returns/${data.id}`)
                    .then(function(response) {
                        sweetAlertAutoClose('success', response.data.message);
                        inst.table.setData();
                    })
                    .catch(function(error) {
                        alert('something went wrong')
                        console.error(error)
                    });

            },
            isAllowed() {
                return document.getElementById('btnUserType').value == 'admin';
            },
            initializeTabulator() {

                let ins = this;
                let isAllowedToDelete = this.isAllowed();
                this.table = new Tabulator("#returns-table", {
                    height: '700',
                    pagination: true, //enable.
                    paginationSize: 50,
                    ajaxURL: '/api/returns',
                    layout: "fitColumns",
                    columns: [{
                            title: "tracking number",
                            field: "track_number",
                            headerFilter: true
                        },
                        {
                            title: "order number",
                            field: "order_number",
                            headerFilter: true

                        },
                        {
                            title: "status",
                            field: "description",
                            headerFilter: true
                        },
                        {
                            title: "store",
                            field: "name",
                            headerFilter: true
                        },
                        {
                            title: "created by",
                            field: "createdBy",
                            headerFilter: true
                        },
                        {
                            title: "created At",
                            field: "created_at",
                            headerFilter: true
                        },
                        {
                            title: "updated by",
                            field: "updatedBy",
                            headerFilter: true
                        },
                        {
                            title: "updated At",
                            field: "updated_at",
                            headerFilter: true
                        },
                        {
                            hozAlign: "center",
                            formatter: viewBtn,
                            cellClick: (e, cell) => {
                                var currentData = cell.getRow().getData();
                                location.href = 'returns/' + currentData.id;
                            }
                        },
                        {
                            hozAlign: "center",
                            formatter: deleteBtn,
                            visible: isAllowedToDelete,
                            cellClick: (e, cell) => {
                                var currentData = cell.getRow().getData();
                                ins.onDelete(currentData);
                            }
                        },
                    ],
                });
            }
        },
        mounted() {
            this.initializeTabulator();

            var date = new Date();
            this.updateDate = date.toLocaleString();

            setInterval(() => {
                if (this.table != null) {
                    this.table.setData().then(getCUrrentTime);
                }
            }, 1500000);
        },
    })
</script>


@endsection