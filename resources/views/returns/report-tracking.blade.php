@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Report tracking numbers</h5>
        </div>
        <div class="col-md-6 text-end">
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
            table: null,
        },
        methods: {
            downloadData() {
                this.table.download("csv", "data.csv");
            },
            initializeTabulator() {

                let ins = this;
                this.table = new Tabulator("#returns-table", {
                    height: '700',
                    pagination: true, //enable.
                    paginationSize: 50,
                    ajaxURL: '/api/returns',
                    layout: "fitColumns",
                    columns: [{
                            title: "#",
                            formatter: "rownum",
                            maxWidth: 50
                        },
                        {
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
        mounted() {
            this.initializeTabulator();
        },
    })
</script>


@endsection