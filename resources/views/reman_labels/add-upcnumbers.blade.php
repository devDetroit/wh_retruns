@extends('bootstrap.layouts.layout')

@section('content')

<div id="printLabelApp">

    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <h5>Warehouse: <strong style="color: red;">@{{warehouseDescription}}</strong></h5>
                <h5></h5>
            </div>
        </div>
        <div class="row  mt-4 justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <strong>New UPC Number</strong>
                    </div>
                    <div class="card-body">
                        <form>
                            @csrf
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location">
                            </div>
                            <div class="mb-3">
                                <label for="partnumber" class="form-label">Part Number</label>
                                <input type="text" class="form-control" id="partnumber" name="partnumber">
                            </div>
                            <div class="mb-3">
                                <label for="upc" class="form-label">UPC</label>
                                <input type="text" class="form-control" id="upc" name="upc">
                            </div>
                            <div class="text-end">
                                <button type="reset" class="btn btn-danger">New</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
            warehouse: ''
        },
        methods: {
            getWarehouse(warehouse) {
                this.warehouse = warehouse;
                closeModalButton.click();
            },
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