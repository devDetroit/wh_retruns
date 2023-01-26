<form method="POST" v-on:submit.prevent="updateEmpleado()">
    @csrf
    <div class="modal fade" id="editar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create/Update</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            User
                        </div>
                        <div class="card-body">
                            <template v-if="empleadoData">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Folio">#</label>
                                            <input type="number" name="cUsuarioFolio" class="form-control" v-model="empleadoData.id" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="Identificador">Name</label>
                                            <input type="text" name="cUsuarioNombre" class="form-control" v-model="empleadoData.complete_name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Identificador">Username</label>
                                            <input type="text" name="ccUsuarioApellidoP" class="form-control" v-model="empleadoData.username" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Identificador">E-Mail</label>
                                            <input type="text" name="cUsuarioApellidoM" class="form-control" v-model="empleadoData.email">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-row">
                                        <label for="Apertura">User Type</label>
                                        <select name="cSucursalIdioma" v-model="empleadoData.user_type" class="form-control" required>
                                            <option value="viwer">Viewer</option>
                                            <option value="editor">Editor</option>
                                        </select>
                                    </div>
                                </div>

                            </template>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</form>