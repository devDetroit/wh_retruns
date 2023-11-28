@extends('bootstrap.layouts.layout')

@section('content')
    <div id="addCaliper" class="container">
        <div class="row mt-4">
            <div class="col-md-6">
                <h1> <strong style="color: red;">Add Part</strong></h1>
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header"><strong>New Part</strong></div>
            <div class="card-body">
                <form name="scanningForm" id="scanningForm" @submit.prevent="findCaliper">
                    <div class="row mb-8">
                        <label for="scanning" class="col-md-4 col-form-label text-md-end">
                            Part
                        </label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" v-model="fieldToSearch">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="findCaliper" class="btn btn-primary" v-on:click="findCaliper">Add
                                Part</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br><br>
        <div class="container">
            <div v-if="search" class="card">
                <div class="card-header"><strong>Part Data</strong></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <multiselect v-model="typeSelected" placeholder="Seleccionar tipo de parte .."
                                label="part_description" track-by="id" :options="types" :multiple="false"
                                :taggable="false" selected-label="{!! trans('Seleccionado') !!}"
                                deselect-label="{!! trans('Quitar') !!}" open-direction="bottom" :disabled="partTypeStatus"
                                @input="onTypePartchange(typeSelected.id)">
                                >
                            </multiselect>
                        </div>
                        <div class="col-md-6">
                            <multiselect v-model="familySelected" placeholder="Seleccionar familia .." label="description"
                                track-by="id" :options="families" :multiple="false" :taggable="false"
                                selected-label="{!! trans('Seleccionado') !!}" deselect-label="{!! trans('Quitar') !!}"
                                open-direction="bottom" :disabled="familyStatus">
                            </multiselect>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <div class="container">
            <div v-if="components || parts" class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Components</strong>
                    <div>
                        <template v-if="typeSelected">
                            <template v-if="typeSelected.id == 1">
                                <button class="btn btn-primary" @click="addComponent">Add new component</button>
                            </template>
                        </template>
                    </div>
                </div>

                <div class="card-body">
                    <template v-if="typeSelected">
                        <template v-if="typeSelected.id == 1">
                            <template v-if="parts.length > 0">
                                <template v-for="part in parts">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <multiselect v-model="part.component" placeholder="Seleccionar componente .."
                                                label="part_num" track-by="id" :options="components"
                                                :multiple="false" :taggable="false"
                                                selected-label="{!! trans('Seleccionado') !!}"
                                                deselect-label="{!! trans('Quitar') !!}" open-direction="bottom">
                                            </multiselect>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" v-model="part.quantity">
                                        </div>
                                    </div>
                                    <hr>
                                    <br>
                                </template>
                            </template>
                        </template>
                        <template v-else-if="typeSelected.id == 2">
                            <template v-if="parts.length > 0">
                                <template v-for="part in parts">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <multiselect v-model="part.component" placeholder="Seleccionar componente .."
                                                label="part_num" track-by="id" :options="part.components"
                                                :multiple="false" :taggable="false"
                                                selected-label="{!! trans('Seleccionado') !!}"
                                                deselect-label="{!! trans('Quitar') !!}" open-direction="bottom"
                                                :disabled="part.disableComponent" :required="isRequired">
                                            </multiselect>
                                        </div>
                                        <template v-if="part.component != null">
                                            <div class="col-md-4">
                                                <input type="number" class="form-control" v-model="part.quantity"
                                                    :min="part.min" :max="part.max">
                                            </div>
                                        </template>
                                    </div>
                                    <hr>
                                    <br>
                                </template>
                            </template>
                        </template>

                    </template>


                    <div v-if="parts.length > 1">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong>Actions</strong>
                            <div>
                                <div v-if="typeSelected.id == 2 || partAlreadyExists">
                                    <button class="btn btn-info" @click="generateSerial">Visualize label</button>
                                </div>
                                <div v-else>
                                    <button class="btn btn-success" @click="storePart(1)">Save Part</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="container">
            <div class="card">
                <div class="card-header"><strong>Vista previa etiqueta</strong></div>
                <div class="card-body  text-center">
                    <h4 v-if="serial">@{{ serial }} </h4>
                    <svg id="barcode"></svg>
                </div>
                <template v-if="serial">
                    <div class="card-footer text-end">
                        <button type="button" :disabled="disablePrintButton" class="btn btn-primary"
                            v-on:click="printLabel(2)">Print
                            and
                            Store
                            Part</button>
                        <button type="button" class="btn btn-danger" v-on:click="resetAllVariables">Reset</button>
                    </div>
                </template>
            </div>
        </div>

        <br>
    </div>
    </div>
@endsection


@section('scripts')
    <script src="/js/vue-multiselect.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="{{ asset('js/add-caliper.js') }}"></script>
@endsection
