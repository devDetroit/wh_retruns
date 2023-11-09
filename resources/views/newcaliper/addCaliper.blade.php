@extends('bootstrap.layouts.layout')

@section('content')
    <div id="addCaliper" class="container">
        <div class="row mt-4">
            <div class="col-md-6">
                <h1> <strong style="color: red;">Add Caliper</strong></h1>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header"><strong>Nuevo Caliper</strong></div>
            <div class="card-body">
                <form name="scanningForm" id="scanningForm" @submit.prevent="findCaliper">
                    <div class="row mb-8">
                        <label for="scanning" class="col-md-4 col-form-label text-md-end">
                            CALIPER
                        </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" v-model="fieldToSearch">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="findCaliper" class="btn btn-primary" v-on:click="findCaliper">Add
                                Caliper</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br><br>
        <div class="container">
            <div v-if="components" class="card">
                <div class="card-header"><strong>Add Component</strong></div>
                <div class="card-body">
                    <form name="scanningForm" id="scanningForm" @submit.prevent="findCaliper">


                    <template v-for="component in components">
                    
                    <select name="" id="">
                        
                    </select>
                    
                    </template>





                        {{--  @foreach ($components as $data)
                    <div class="row mb-12">
                        <label for="scanning" class="col-md-4 col-form-label text-md-end">
                        {{ $data->type }}:
                        </label>
                        <div class="col-md-3">
                        <select id="component-dropdown" class="form-control">
                        <option value="">-- Select Component --</option>
                            <option value="{{ $data->type }}">
                                {{ $data->type }}
                            </option>
                        </select>
                        </div>
                        <label for="scanning" class="col-md-1">
                            Qty:
                        </label>
                        <div class="col-md-1">
                            <input type="number" class="form-control" min="0" max="4">
                        </div>
                    </div>
                    <br>
                @endforeach --}}
                        <button type="button" id="btn-upload" class="btn btn-success float-right">Upload</button>
                    </form>
                </div>
            </div>
            <br>
        </div>
    </div>
@endsection


@section('scripts')
    <script src=" https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="{{ asset('js/add-caliper.js') }}"></script>
@endsection
