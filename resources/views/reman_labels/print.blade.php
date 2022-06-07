@php
$isValid = isset($computer[0]);
@endphp

@extends('bootstrap.layouts.layout')

@section('content')

<div id="printLabelApp">
    @if($isValid)
    <div class="row mt-4">
        <div class="col-md-12 text-end">
            <h5>Impresora asignada: <strong>{{$computer[0]->printer}}</strong></h5>
        </div>
    </div>
    @endif
    <div class="container">
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


</div>
@endsection



@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    const app = new Vue({
        el: '#printLabelApp',
        data: {
            upc: '',
            partNumber: '',
            location: '',
            upcNotFoundList: []
        },
        methods: {
            generateLabel() {

                if (this.upc.trim().length <= 0) {
                    if (!is_integer(this.upc)) {
                        sweetAlertAutoClose('warning', "Numero UPC ingresado no es numerico");
                        this.upc = '';
                        return;
                    }
                    sweetAlertAutoClose('warning', "No se escaneo ni un UPC");
                    this.upc = '';
                    return;
                }

                let instance = this;
                axios({
                        method: 'get',
                        url: '/api/upc',
                        params: {
                            upc: instance.upc.trim()
                        }
                    })
                    .then(function(response) {
                        console.log(response)
                        if (response.data.upc.length <= 0) {
                            sweetAlertAutoClose('error', "UPC no encontrado")
                            instance.clearFields()
                        } else {
                            instance.partNumber = response.data.upc[0]['Item'];
                            instance.location = response.data.upc[0]['LocationNumber'];
                            instance.generateCodeBar()
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
                if (this.partNumber.length <= 0 || this.location.length <= 0) {
                    sweetAlertAutoClose('error', "Informacion incompleta o campos vacios")
                    return
                }
                printButton.disabled = true;
                let instance = this;
                axios({
                        method: 'get',
                        url: '/api/print',
                        params: {
                            partNumber: instance.partNumber.trim(),
                            location: instance.location.trim()
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
                this.upc = '';
                this.partNumber = '';
                this.location = '';
                document.getElementById('barcode').replaceChildren();
            }
        },
    })
</script>


@endsection