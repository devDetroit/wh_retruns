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
            <h1> <strong style="color: red;">Reman Calipers</strong></h1>
        </div>
        <div class="col-md-6 text-end">
            <h5>Impresora asignada: <strong>{{$computer[0]->printer}}</strong></h5>
        </div>
    </div>
    @endif
    <div class="container">
        <div class=" row mt-4 justify-content-center">
            <div class="col-md-10">
                @if($isValid)
                <x-print-label isCaliper="true"></x-print-label>
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
                        <h1>@{{family}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @{{ partNumber}} </h1>
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
    @include('components.AddFamily')
</div>
@endsection



@section('scripts')
<link rel="stylesheet" href="/css/vue-multiselect.min.css">
<script src="/js/vue-multiselect.js"></script>
<script src=" https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    const app = new Vue({
        el: '#printLabelApp',
        data: {
            fieldToSearch: '',
            upc: '',
            family: '',
            partNumber: '',
            location: '',
            warehouse: '',
            newWindow: null,
            families: [],
            familyDataSelect: null,
            familyDataInput: null,
            createOrSelect: true,
            modal: null,
            part_number: null
        },

        methods: {
            getWarehouse(warehouse) {
                this.warehouse = warehouse;
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
                        url: '/labels/calipers/get/' + instance.fieldToSearch.trim(),
                    })
                    .then(function(response) {
                        if (response.data.state == false) {
                            sweetAlertAutoClose('error', "UPC no encontrado")
                            instance.clearFields()
                        } else {

                            if (response.data.data.family == null || response.data.data.family == '') {

                                instance.getFamilies()

                                const modalButton = document.querySelector('#modalButton');
                                instance.modal = new bootstrap.Modal(document.querySelector('#exampleModal'));
                                instance.modal.show();
                            }

                            instance.partNumber = response.data.data.part_number;
                            instance.family = response.data.data.family;
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
            StoreFamily() {

                var url = "/labels/families/store";
                var data = {
                    'select': this.familyDataSelect,
                    'input': this.familyDataInput,
                    'part_number': this.partNumber
                };
                axios.post(url, data).then((response) => {
                    this.modal.hide();
                    this.fieldToSearch = ''
                    this.partNumber = response.data.part_number;
                    this.family = response.data.family;
                }).catch(function(error) {
                    console.log(error);
                });
            },
            generateCodeBar() {
                JsBarcode("#barcode", this.family + ' ' + this.partNumber, {
                    displayValue: false,
                    width: 5,
                    height: 200
                });
            },
            getFamilies() {
                let instance = this;
                axios({
                        method: 'get',
                        url: '/labels/families/get',
                    })
                    .then(function(response) {

                        instance.families = response.data
                    }).catch(error => {
                        sweetAlertAutoClose('error', "Error procesando la informacion")
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
                        url: '/labels/caliper/print',
                        params: {
                            partNumber: instance.partNumber.trim(),
                            family: instance.family.trim(),
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
                this.family = '';
                document.getElementById('barcode').replaceChildren();
                document.getElementById('scanningInput').focus();
            }
        },
        mounted() {},
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
        },
        components: {
            Multiselect: window.VueMultiselect.default,
        },
    })
</script>

@endsection