@php
$isValid = isset($computer[0]);
@endphp

@extends('bootstrap.layouts.layout')

@section('content')

<div id="printLabelApp">
    @if($isValid)
    <div class="row mt-4">
        <div class="col-md-6">
            <h5>Impresion etiquetas:</h5>
            <h1> <strong style="color: red;">@{{warehouseDescription}}</strong></h1>
        </div>
        <div class="col-md-6 text-end">
            <h5>Impresora asignada: <strong>{{$computer[0]->printer}}</strong></h5>
        </div>
    </div>
    @endif
    <div class="container" v-show="!showInputs">
        <div class="row  mt-4 justify-content-center">
            <div class="col-md-10">
                @if($isValid)
                <x-print-label></x-print-label>
                @else
                <x-printer-not-found></x-printer-not-found>
                @endif
            </div>
        </div>
        @if($isValid)
        <div class="row  mt-4 justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><strong>Vista previa etiqueta</strong></div>
                    <div class="card-body  text-center">
                        <h1>Part #: @{{partNumber}}</h1>
                        <svg id="barcode"></svg>
                        <h4>@{{location}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Made in china</h4>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" id="printButton" class="btn btn-primary" v-on:click="printLabel">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <x-location-labels></x-location-labels>

</div>
@endsection



@section('scripts')
<script src=" https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    const app = new Vue({
        el: '#printLabelApp',
        data: {
            fieldToSearch: '',
            upc: '',
            partNumber: '',
            location: '',
            warehouse: '',
            newWindow: null
        },
        methods: {
            getWarehouse(warehouse) {
                this.warehouse = warehouse;
                closeModalButton.click();
            },
            generateLabel() {

                const startWithFilter = ['43', '53', '50', '51']
                if (this.fieldToSearch.trim().length <= 0) {
                    sweetAlertAutoClose('warning', "No se escaneo ni un UPC");
                    this.clearFields()
                    return;
                }

                let instance = this;
                axios({
                        method: 'get',
                        url: '/upc',
                        params: {
                            upc: instance.fieldToSearch.trim(),
                            warehouse: instance.warehouse
                        }
                    })
                    .then(function(response) {
                        if (response.data.upc.length <= 0) {
                            sweetAlertAutoClose('error', "UPC no encontrado")
                            instance.clearFields()
                        } else {

                            instance.partNumber = response.data.upc[0]['Item'];
                            instance.location = response.data.upc[0]['LocationNumber'];
                            instance.upc = response.data.upc[0]['UPC'];
                            instance.generateCodeBar()

                            if (instance.partNumber.length == 6) {
                                if (startWithFilter.indexOf(instance.partNumber.substring(0, 2)) >= 0) {
                                    window.open("https://www.detroitaxle.com/?s=" + instance.partNumber, 'detroit', 'location=yes,toolbar=yes,menubar=yes,directories=yes', false);
                                }
                            }
                        }

                    }).catch(error => {
                        sweetAlertAutoClose('error', "Error procesando la informacion")
                        console.error(error)
                        instance.clearFields()
                    });
            },
            generateCodeBar() {
                JsBarcode("#barcode", this.upc, {
                    displayValue: false,
                    width: 5,
                    height: 200
                });
            },
            printLabel() {
                if (this.partNumber.length <= 0) {
                    sweetAlertAutoClose('error', "Informacion incompleta o campos vacios")
                    return
                }
                printButton.disabled = true;
                let instance = this;
                axios({
                        method: 'get',
                        url: '/print',
                        params: {
                            upc: instance.upc.toString().trim(),
                            partNumber: instance.partNumber.trim(),
                            location: instance.location
                        }
                    })
                    .then(function(response) {
                        sweetAlertAutoClose(response.data.returnValue <= 0 ? 'error' : 'success', response.data.message)
                        instance.clearFields()
                        printButton.disabled = false;
                    }).catch(error => {
                        sweetAlertAutoClose('error', "Error procesando la informacion")
                        console.error(error)
                        instance.clearFields()
                        printButton.disabled = false;
                    });
            },
            clearFields() {
                this.fieldToSearch = '';
                this.upc = '';
                this.partNumber = '';
                this.location = '';
                document.getElementById('barcode').replaceChildren();
                document.getElementById('scanningInput').focus();
            }
        },
        mounted() {
            locationButton.click();
        },
        computed: {
            warehouseDescription: function() {
                var description = ''
                switch (this.warehouse) {
                    case 'jrz':
                        description = 'REMAN'
                        break;

                    case 'elp':
                        description = 'ELP WH'
                        break;

                    default:
                        description = ''
                        break;
                }
                return description;
            },
            showInputs: function() {
                return this.warehouse.length <= 0;
            }
        }
    })
</script>


@endsection