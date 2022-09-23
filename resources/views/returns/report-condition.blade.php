@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row mt-4">
        <div class="col-md-4">
            <h3>Returns Condition Report</h3>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col">
                    <h5>Filter by date</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="date" class="form-control form-control-sm" v-model="startDate">
                </div>
                <div class="col-md-6">
                    <input type="date" class="form-control form-control-sm" v-model="endDate">
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-sm btn-dark" v-on:click="downloadData">Download CSV File</button>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div id="returns-table"></div>
        </div>
    </div>
</div>
@endsection



@section('scripts')

<script>
    const app = new Vue({
        el: '#indexapp',
        data: {
            // records: [],
            startDate: new Date().toLocaleDateString(),
            endDate: new Date().toLocaleDateString(),
            table: null,
        },
        methods: {
            downloadData() {
                this.table.download("csv", "data.csv");
            },
            /* initializeData() {
                let ins = this
                this.records = [];

                axios({
                    method: 'get',
                    url: 'api/returnsCondition',
                    params: {
                        startDate: ins.startDate,
                        endDate: ins.endDate
                    }
                })
                .then(function(response) {
                    ins.records = response.data.returnRecords;
                    ins.initializeTabulator(response.data.returnRecords);
                });
            }, */
            initializeTabulator() {
                let ins = this;
                this.table = new Tabulator("#returns-table", {
                    height: '700',
                    pagination: true, //enable.
                    paginationSize: 25,
                    // data: data,
                    ajaxURL: '/api/returnsCondition',
                    ajaxParams: {
                        startDate: this.startDate,
                        endDate: this.endDate
                    },
                    layout: "fitColumns",
                    columns: [{
                            title: "#",
                            formatter: "rownum",
                            maxWidth: 50
                        },
                        {
                            title: "Store",
                            field: "store_name",
                            headerFilter: true
                        },
                        {
                            title: "Track Number",
                            field: "track_number",
                            headerFilter: true

                        },
                        {
                            title: "Order Number",
                            field: "order_number",
                            headerFilter: true
                        },
                        {
                            title: "Order Status",
                            field: "order_status",
                            headerFilter: true
                        },
                        {
                            title: "Created at",
                            field: "created_at",
                            headerFilter: true
                        },
                        {
                            title: "Created by",
                            field: "created_by",
                            headerFilter: true
                        },
                        {
                            title: "Updated at",
                            field: "updated_at",
                            headerFilter: true
                        },
                        {
                            title: "Updated by",
                            field: "updated_by",
                            headerFilter: true
                        },
                        {
                            title: "Part Number",
                            field: "partnumber",
                            headerFilter: true
                        },
                        {
                            title: "PN Description",
                            field: "pn_status",
                            headerFilter: true
                        },
                    ],
                });
            }
        },
        mounted() {
            this.initializeTabulator();
        },
        watch: {
            startDate: function() {
                if (this.startDate != null && this.endDate != null) {
                    if (this.table != null)
                        this.table.destroy();
                    this.initializeTabulator();
                }
            },
            endDate: function() {
                if (this.startDate != null && this.endDate != null) {
                    if (this.table != null)
                        this.table.destroy();
                    this.initializeTabulator();
                }
            }
        }
    })
</script>


@endsection