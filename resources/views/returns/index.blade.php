@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row justify-content-md-center mt-4">
        <div class="col-md-10">
            <div class="card shadow-sm p-2 bg-body rounded">
                <div class="card-body">
                    <h5 class="card-title text-center">ELP Wherehouse Returns</h5>
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label for="trackNumber" class="form-label">Filter by Tracking Number:</label>
                            <input type="text" class="form-control" id="trackNumber" v-on:keyup="searchReturns" v-model="trackNumber">
                        </div>
                        <div class="col-md-4">
                            <label for="trackNumber" class="form-label">Filter by Order Number:</label>
                            <input type="text" class="form-control" id="trackNumber" v-on:keyup="searchReturns" v-model="trackNumber">
                        </div>
                        <div class="col-md-4">
                            <label for="inputState" class="form-label">Filter by Status:</label>
                            <select id="inputState" class="form-select" v-model="status" v-on:change="searchByStatus">
                                <option value="" selected>Select status</option>
                                @foreach ($return_status as $status)
                                <option value="{{ $status->description }}"> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-md-10">
            <div class="mt-4" id="returns-table"></div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/vue@3"></script>
<script>
    var viewBtn = function(cell, formatterParams, onRendered) { //plain text value
        return '<button type="button" class="btn btn-sm btn-primary">View Details</button>';
    };

    var deleteBtn = function(cell, formatterParams, onRendered) { //plain text value
        return '<button type="button" class="btn btn-sm btn-danger">Delete</button>';
    };

    Vue.createApp({
        data() {
            return {
                table: null,
                trackNumber: '',
                status: ''
            }
        },
        methods: {
            searchReturns() {
                this.table.setFilter("track_number", "starts", this.trackNumber);
            },
            searchByStatus() {
                if (this.status.length > 0)
                    this.table.setFilter("returnstatus.description", "=", this.status);
                else
                    this.table.clearFilter();
            },
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
            initializeTabulator() {

                let ins = this;
                this.table = new Tabulator("#returns-table", {
                    height: "100%",
                    ajaxURL: '/api/returns',
                    layout: "fitColumns",
                    columns: [{
                            title: "Row Num",
                            formatter: "rownum",
                        },
                        {
                            title: "Tracking Number",
                            field: "track_number",
                        },
                        {
                            title: "Order Number",
                            field: "order_number",
                        },
                        {
                            title: "Status",
                            field: "returnstatus.description",
                        },
                        {
                            title: "Upload By",
                            field: "user.name",
                        },
                        {
                            title: "Upload At",
                            field: "created_at",
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
        },
    }).mount('#indexapp')
</script>


@endsection