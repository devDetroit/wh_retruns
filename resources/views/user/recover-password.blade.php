@extends('bootstrap.layouts.layout')

@section('content')
<div id="reset" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{!! trans('empleado.rc') !!}</div>

                <div class="card-body">


                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{!! trans('empleado.nc') !!}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" v-model="password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{!! trans('empleado.cc') !!}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" v-model="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-dark" v-on:click.prevent="verificarPasswords()">
                                {!! trans('empleado.sc') !!}
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Vue-->
<script>
    new Vue({
        el: "#reset",
        created: function() {

        },
        data: {
            password: '',
            password_confirmation: '',
        },
        computed: {

        },
        methods: {

            resetData: function() {
                this.password = '';
                this.password_confirmation = '';
            },

            verificarPasswords: function() {
                if (this.password != this.password_confirmation) {
                    alert("Las constraseñas no coinciden, vuelve a intentar");
                } else {
                    // toastr.info("Las contraseñas coinciden, procesando...");
                    this.resetPassword();
                }
            },

            resetPassword: function() {
                var url = "/admin/password";
                var data = {
                    'password': this.password_confirmation
                };
                axios.post(url, data).then(() => {
                    alert("La contraseña se ha actualizado correctamente");
                    this.resetData();
                    setTimeout(window.location.replace("/returns"), 15000);
                }).catch(function(error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
            },
        }
    });
</script>
@endsection