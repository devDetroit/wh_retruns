<form method="POST" v-on:submit.prevent="resetPassword(empleadoData)">
  @csrf
  <div class="modal fade" id="reset">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reset Password</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <template v-if="empleadoData">
            <div class="form-group">
              <label> Are you sure you want to reset the password of the user:
                <span><i> @{{ empleadoData.complete_name }} </i></span> ?
              </label>
            </div>
          </template>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger">Confirm</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</form>