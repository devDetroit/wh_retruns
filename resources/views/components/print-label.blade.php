<div class="card">
    <div class="card-header"><strong>Impresion de etiqueta</strong></div>
    <div class="card-body">
        <form name="scanningForm" id="scanningForm" @submit.prevent="generateLabel">
            <div class="row mb-3">
                <label for="scanning" class="col-md-4 col-form-label text-md-end">Numero UPC:</label>
                <div class="col-md-6">
                    <input id="scanningInput" type="text" class="form-control" name="scanning" required="" autocomplete="scanning" v-model="upc">
                </div>
            </div>
        </form>
    </div>
</div>