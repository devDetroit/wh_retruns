@php
$isValid = isset($computer[0]);
@endphp

@extends('bootstrap.layouts.layout')

@section('content')
<div id="report" class="container-fluid">
    <div className="row justify-content-center">
        <div className="col-md-10">
            <form method="POST" @submit.prevent="getRecords">
                <div class="card shadow bg-body rounded">
                    <div class="card-header">
                        Filtros
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="fechaDesde">Desde</label>
                                    <div class="input-group">
                                        <input class="form-control" type="date" name="fechaDesde" v-model="busqueda.fechai" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label" for="fechaHasta">Hasta</label>
                                    <div class="input-group">
                                        <input class="form-control" type="date" name="fechaHasta" v-model="busqueda.fechaf" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-row">
                                    <label for="Apertura">Tipo de Parte</label>
                                    <select name="cSucursalIdioma" v-model="busqueda.type" class="form-control" required>
                                        <template v-for="type in parttypes">
                                            <option :value="type.id">@{{ type.part_description }}</option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">

                        <button type="submit" class="btn btn-sm btn-primary float-right" style="margin-right: 5px;">
                            <i class="fas fa-search"></i>
                            <span>Buscar</span>
                        </button>

                    </div><!-- /.card-footer -->
                </div>
            </form>
            <br><br>
            <div class="card shadow p-1 bg-body rounded">
                <div class="card-header text-end">
                    <button type="button" class="btn btn-sm btn-success" v-on:click="downloadData">Download CSV
                        File</button>
                </div>
                <div class="card-body">
                    <div class="text-end">
                        <div id="reportes"></div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection



@section('scripts')
<script src="{{ asset('js/report-detallado.js') }}"></script>
@endsection