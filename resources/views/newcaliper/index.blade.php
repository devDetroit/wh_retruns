@extends('bootstrap.layouts.layout')

@section('content')
<div id="printLabelApp">

    <div class="row mt-4">
        <div class="col-md-6">
            <h1> <strong style="color: red;">Registro de Parte</strong></h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class=" row">
            <div class="col-md-4">

                <div class="card">
                    <div class="card-header"><strong>Busqueda de Parte</strong></div>
                    <div class="card-body">
                        <form name="scanningForm" id="scanningForm" @submit.prevent="findCaliper">
                            <div class="row mb-3">
                                <label for="scanning" class="col-md-4 col-form-label text-md-end">
                                    NUMERO DE PARTE
                                </label>
                                <div class="col-md-4">
                                    <input id="scanningInput" type="text" class="form-control" name="scanning" required="" autocomplete="scanning" v-model="fieldToSearch">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="cleanButton" class="btn btn-danger" v-on:click="clearFields">Limpiar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Componentes</strong></div>
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
                                <template v-if="caliper">
                                    <template v-for="cal in caliper.components">
                                        <tr>
                                            <td class="col-md-4"> <img :src="cal.component.type.url" alt="My Image" width="85" height="85"></td>
                                            <td class="col-md-4">@{{ cal.component.part_num }}</td>
                                            <td class="col-md-4">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <button class="btn btn-sm btn-danger" @click="cal.quantity > 0 ? cal.quantity-- : cal.quantity = 0">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input :disabled="true" type="number" class="form-control" v-model="cal.quantity" required="required">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <button class="btn btn-sm btn-primary" @click="cal.quantity < cal.MaxValue ? cal.quantity++ : cal.quantity">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <p>No info to show</p>

                                </template>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Vista previa etiqueta</strong></div>
                    <div class="card-body  text-center">
                        <h4 v-if="serialNumber">@{{ serialNumber }} </h4>
                        <svg id="barcode"></svg>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" id="printButton" class="btn btn-primary" v-on:click="printLabel">Imprimir</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('components.AddFamily')
</div>
@endsection



@section('scripts')
<script src=" https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="{{ asset('js/print-caliper-comp.js') }}"></script>
@endsection