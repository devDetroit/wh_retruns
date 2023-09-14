<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar/Seleccionar Familia de Caliper</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-check form-switch">
                    <multiselect v-model="familyDataSelect" placeholder="Search Family" label="family" track-by="family"
                        :options="families" :multiple="false" :taggable="false" :close-on-select="false"
                        select-label="Seleccionar" selected-label="Seleccionado" deselect-label="Eliminar"
                        open-direction="bottom">
                    </multiselect>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" v-on:click="StoreFamily">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
