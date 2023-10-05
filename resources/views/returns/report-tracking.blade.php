@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row justify-content-center mt-4">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <h4 class="card-title">Tracking Numbers Report</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">From:</span>
                                <input type="date" class="form-control form-control-sm" v-model="startDate">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">To:</span>
                                <input type="date" class="form-control form-control-sm" v-model="endDate">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-sm btn-success" v-on:click="applySearch">Search</button>
                            <button type="button" class="btn btn-sm btn-dark" v-on:click="downloadData">Download CSV File</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-10">
            <div id="returns-table"></div>
        </div>
    </div>
</div>
@endsection



@section('scripts')

<script>
    var customAccessorDownload = function(value, data, type, params, column, row) {
        return value + ' .'; //return the new value for the cell data.
    }

    const app = new Vue({
        el: '#indexapp',
        data: {
            startDate: new Date().toLocaleDateString(),
            endDate: new Date().toLocaleDateString(),
            table: null,
        },
        methods: {
            downloadData() {
                this.table.download("csv", "data.csv");
            },
            applySearch() {
                if (this.table != null)
                    this.table.setData();
                else
                    this.initializeTabulator();
            },
            initializeTabulator() {
                let ins = this;
                this.table = new Tabulator("#returns-table", {
                    height: '700',
                    pagination: true, //enable.
                    paginationSize: 25,
                    ajaxURL: '/api/returns',
                    ajaxParams: function() {
                        return {
                            startDate: ins.startDate,
                            endDate: ins.endDate
                        }
                    },
                    layout: "fitColumns",
                    columns: [{
                            title: "#",
                            formatter: "rownum",
                            maxWidth: 50
                        },
                        {
                            title: "tracking number",
                            field: "track_number",
                            accessorDownload: customAccessorDownload,
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
                        },
                        {
                            title: "created At",
                            field: "created_at",
                        },
                        {
                            title: "updated by",
                            field: "updatedBy",
                        },
                        {
                            title: "updated At",
                            field: "updated_at",
                        },
                    ],
                });
            }
        },
        /* mounted() {
            this.initializeTabulator();
        }, */
        watch: {
            startDate: function() {
                if (this.startDate > this.endDate)
                    this.endDate = this.startDate;
            },
            endDate: function() {
                if (this.startDate > this.endDate)
                    this.endDate = this.startDate;
            }
        }
    })
</script>


@endsection