@php
$isValid = isset($computer[0]);
@endphp

@extends('bootstrap.layouts.layout')

@section('content')
<div id="checked">
    <div class="row mt-4">
        <div class="col-md-6">
            <h1> <strong style="color: red;">Recibimiento de Parte</strong></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><strong>Busqueda de Parte</strong></div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label for="scanning" class="col-md-4 col-form-label text-md-end">
                                    NUMERO SERIAL
                                </label>
                                <div class="col-md-4">
                                    <input id="scanningInput" type="text" class="form-control" name="scanning" required="" autocomplete="scanning" v-on:keyup.enter="findCaliper" v-model="fieldToSearch">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="cleanButton" class="btn btn-danger" v-on:click="clearFields">Limpiar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><strong>Ultimos registros</strong></div>
                        <div class="card-body">
                            <table class="table table-striped" v-if="lasts">
                                <thead>
                                    <tr>
                                        <th scope="col">Numero Serial</th>
                                        <th scope="col">Fechad de Recibido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="last in lasts">
                                        <td>@{{ last.serial_number }}</td>
                                        <td>@{{ last.received_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @endsection



    @section('scripts')
    <script src="{{ asset('js/check-caliper.js') }}"></script>
    @endsection