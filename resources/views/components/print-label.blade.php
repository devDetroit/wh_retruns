@props(['isCaliper' => false])
<div class="card">
    <div class="card-header"><strong>Impresion de etiqueta</strong></div>
    <div class="card-body">
        <form name="scanningForm" id="scanningForm" @submit.prevent="generateLabel">
            <div class="row mb-3">
                <label for="scanning" class="col-md-4 col-form-label text-md-end"> @if(!$isCaliper) Numero UPC o @endif Num de Parte:</label>
                <div class="col-md-4">
                    <input id="scanningInput" type="text" class="form-control" name="scanning" required="" autocomplete="scanning" v-model="fieldToSearch">
                </div>
                <div class="col-md-2">
                    <button type="button" id="printButton" class="btn btn-danger" v-on:click="clearFields">Limpiar</button>
                </div>
            </div>
        </form>
    </div>
</div>