@extends('bootstrap.layouts.layout')


@section('styles')
<link rel="stylesheet" href="/css/circle.css">


@endsection

@section('content')

<div id="printLabelApp">

    <div class="row mt-2 justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            <p style="font-size: 5em;  color: black; font-weight: bold;"> STATION 1</p>
        </div>
    </div>

    <div class="row mt-2 justify-content-center">
        <div class="col-md-6 d-flex justify-content-center">
            <div class="c100 p50 big">
                <span>50%</span>
                <div class="slice">
                    <div class="bar"></div>
                    <div class="fill"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row  mt-2 justify-content-center">
        <div class="col-md-6">
            <table class="table table-borderless table-sm text-center">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">
                            <p style="font-size: 5em;  color:#307bbb;">ACTUAL</p>
                        </th>
                        <th scope="col">
                            <p style="font-size: 5em; color:green;">TOTAL</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <p style="font-size: 5em; color:#307bbb; font-weight: bold;">5000</p>
                        </td>
                        <td>
                            <p style="font-size: 5em; color:green; font-weight: bold;">5000</p>
                        </td>
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
        el: '#printLabelApp',
        data: {},
        methods: {},
    })
</script>


@endsection