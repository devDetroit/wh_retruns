@extends('bootstrap.layouts.layout')

@section('content')

<div class="row border-bottom">
    <div class="col-md-12">
        <h4 class="mt-2">Returns</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="mt-4" id="returns-table"></div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    var tabledata = [{
            id: 1,
            name: "Oli Bob",
            age: "12",
            col: "red",
            dob: ""
        },
        {
            id: 2,
            name: "Mary May",
            age: "1",
            col: "blue",
            dob: "14/05/1982"
        },
        {
            id: 3,
            name: "Christine Lobowski",
            age: "42",
            col: "green",
            dob: "22/05/1982"
        },
        {
            id: 4,
            name: "Brendon Philips",
            age: "125",
            col: "orange",
            dob: "01/08/1980"
        },
        {
            id: 5,
            name: "Margret Marmajuke",
            age: "16",
            col: "yellow",
            dob: "31/01/1999"
        },
    ];

    var printIcon = function(cell, formatterParams, onRendered) { //plain text value
        return '<button type="button" class="btn btn-sm btn-primary">View Details</button>';
    };


    var table = new Tabulator("#returns-table", {
        height: "100%",
        ajaxURL: '/api/returns',
        layout: "fitColumns",
        columns: [{
                title: "Return Num",
                formatter: "rownum",
            },
            {
                title: "Tracking Number",
                field: "track_number",
            },
            {
                title: "Status",
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
                formatter: printIcon
            },
        ],
    });

    //trigger an alert message when the row is clicked
</script>


@endsection