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
                            <label for="trackNumber" class="form-label">Filter by tracking or order Number:</label>
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
                        <div class="col-md-2">
                            <label class="form-label" style="color: transparent;">Remove filters:</label>
                            <button type="button" class="btn btn-sm btn-danger form-control" v-on:click="removefilters">Remove all filters</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <input type="hidden" id="btnUserType" value="{{ Auth::user()->user_type }}">
    <div class="row justify-content-md-center">
        <div class="col-md-12">
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
                this.table.setFilter([
                    [{
                            field: "order_number",
                            type: "starts",
                            value: this.trackNumber
                        },
                        {
                            field: "track_number",
                            type: "starts",
                            value: this.trackNumber
                        },
                    ]
                ]);
            },
            searchByStatus() {
                if (this.status.length > 0)
                    this.table.setFilter("returnstatus.description", "=", this.status);
                else
                    this.table.clearFilter();
            },
            removefilters() {
                this.table.clearFilter(true);
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
            isAllowed() {
                return document.getElementById('btnUserType').value == 'admin';
            },
            initializeTabulator() {

                let ins = this;
                let isAllowedToDelete = this.isAllowed();
                this.table = new Tabulator("#returns-table", {
                    height: "100%",
                    ajaxURL: '/api/returns',
                    layout: "fitColumns",
                    columns: [{
                            title: "#",
                            formatter: "rownum",
                        },
                        {
                            title: "tracking number",
                            field: "track_number",
                        },
                        {
                            title: "order number",
                            field: "order_number",
                        },
                        {
                            title: "status",
                            field: "returnstatus.description",
                        },
                        {
                            title: "created by",
                            field: "created_by.complete_name",
                        },
                        {
                            title: "created At",
                            field: "created_at",
                        },
                        {
                            title: "updated by",
                            field: "updated_by.complete_name",
                        },
                        {
                            title: "updated At",
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
        },
    }).mount('#indexapp')
</script>


@endsection