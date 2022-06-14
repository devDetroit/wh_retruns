 <!-- Button trigger modal -->
 <button type="button" id="locationButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#locationModel" style="display: none;">
     Launch demo modal
 </button>

 <!-- Modal -->
 <div class="modal fade" id="locationModel" tabindex="-1" aria-labelledby="locationModelLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="locationModelLabel">Seleccione Localidad</h5>
                 <button type="button" id="closeModalButton" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-6">
                         <button type="button" class="btn btn-outline-primary" style="width: 100%;height: 200px;" v-on:click="getWarehouse('jrz')">JRZ</button>
                     </div>
                     <div class="col-md-6">
                         <button type="button" class="btn btn-outline-danger" style="width: 100%;height: 200px;" v-on:click="getWarehouse('elp')">ELP</button>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>