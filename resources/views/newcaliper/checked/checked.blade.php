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
        <div class="container-fluid">
            <div class=" row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><strong>Busqueda de Parte</strong></div>
                        <div class="card-body">
                            <form name="scanningForm" id="scanningForm" @submit.prevent="findCaliper">
                                <div class="row mb-3">
                                    <label for="scanning" class="col-md-4 col-form-label text-md-end">
                                        NUMERO SERIAL
                                    </label>
                                    <div class="col-md-4">
                                        <input id="scanningInput" type="text" class="form-control" name="scanning"
                                            required="" autocomplete="scanning" v-model="fieldToSearch">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="printButton" class="btn btn-danger"
                                            v-on:click="clearFields">Limpiar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
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


                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><strong>Componentes</strong>
                            <template v-if="part">
                                <div class="text-end">
                                    <button type="button" class="btn btn-primary float-right" v-on:click="saveComponents">
                                        Confirm components
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div class="card-body  text-center">
                            <table class="table">
                                <thead>
                                    <tr>

                                        <th scope="col">Tipo</th>
                                        <th scope="col">Componente</th>
                                        <th scope="col">Cantidad</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="part">
                                        <template v-for="cal in part.details">
                                            <tr>
                                                <td class="col-md-4"> <img :src="cal.component.type.url" alt="My Image"
                                                        width="85" height="85"></td>
                                                <td class="col-md-4">@{{ cal.component.part_num }}</td>
                                                <td class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <button class="btn btn-sm btn-danger"
                                                                @click="cal.quantity > 0 ? cal.quantity-- : cal.quantity = 0">
                                                                <i class="fa-solid fa-minus"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input :disabled="true" type="number"
                                                                class="form-control" v-model="cal.quantity"
                                                                required="required">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button class="btn btn-sm btn-primary"
                                                                @click="cal.quantity < cal.MaxValue ? cal.quantity++ : cal.quantity">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <p>No informacion para mostrar</p>

                                    </template>
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
