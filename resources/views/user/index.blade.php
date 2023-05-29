@extends('bootstrap.layouts.layout')

@section('content')
<div id="empleado" class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
            <a class="btn float-right btn-small btn-primary" v-on:click.prevent="createEmpleado()">Create User &nbsp;&nbsp;<i class="fas fa-plus"></i></a>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-custom">
                            <thead class="text-center">
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
                                        <input class="form-control" type="text" placeholder="Name" v-model="busqueda.name" v-on:change="getEmpleados()">
                                    </th>
                                    <th>
                                        <input class="form-control" type="text" placeholder="Username" v-model="busqueda.username" v-on:change="getEmpleados()">
                                    </th>
                                    <th>
                                        <input class="form-control" type="text" placeholder="E-mail" v-model="busqueda.email" v-on:change="getEmpleados()">
                                    </th>
                                    <th>
                                        <input class="form-control" type="text" placeholder="Type" v-model="busqueda.user_type" v-on:change="getEmpleados()">
                                    </th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="empleados && empleados.length > 0">
                                    <template v-for="empleado in empleados">
                                        <tr class="text-center">
                                            <td>@{{ empleado.id }}</td>
                                            <td class="text-wrap">@{{ empleado.complete_name }}</td>
                                            <td class="text-wrap">@{{ empleado.username }}</td>
                                            <td class="text-wrap">@{{ empleado.email }}</td>
                                            <td class="text-wrap">@{{ empleado.user_type }}</td>
                                            <td class="text-right">
                                                <a class="btn btn-sm btn-success" v-on:click.prevent="editEmpleado(empleado)"><i class="fas fa-pencil-alt"></i></a>
                                                <a class="btn btn-sm btn-warning" v-on:click.prevent="confirmResetPassword(empleado)"><i class="fas fa-key"></i></a>
                                                <a class="btn btn-sm btn-danger" v-on:click.prevent="removeEmpleado(empleado)"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <template v-else>
                                    <td colspan="7">
                                        <p class="text-center">{!! trans('empleado.nohay') !!}</p>
                                    </td>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                        Showing from
                        @{{ pagination.to }} to @{{ pagination.total }} elements
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers float-right" id="example2_paginate">
                        <ul class="pagination">
                            <li v-if="pagination.current_page > 1" class="paginate_button page-item previous" id="example2_previous">
                                <a href="#" @click.prevent="changePage(pagination.current_page - 1)" aria-controls="example2" data-dt-idx="0" tabindex="0" class="page-link">
                                    Previous
                                </a>
                            </li>
                            <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']" class="paginate_button page-item">
                                <a href="#" @click.prevent="changePage(page)" aria-controls="example2" data-dt-idx="1" tabindex="0" class="page-link">
                                    @{{ page }}
                                </a>
                            </li>
                            <li v-if="pagination.current_page < pagination.last_page" class="paginate_button page-item next" id="example2_next">
                                <a href="#" @click.prevent="changePage(pagination.current_page + 1)" aria-controls="example2" data-dt-idx="7" tabindex="0" class="page-link">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
                @include('user.modal.resetpassword')
            </div>
            @include('user.modal.deleteEmpleado')
            @include('user.modal.editEmpleado')

        </div>

    </div>



</div>
@endsection

@section('scripts')
<script src="/js/vue-multiselect.js"></script>
<script>
    new Vue({
        el: "#empleado",
        created: function() {
            this.getEmpleados();
        },
        data: {
            checked: false,
            cargaEmpleado: 0,
            busqueda: {
                complete_name: '',
                username: '',
                email: '',
                user_type: '',

            },
            empleados: [],
            empleado: {
                complete_name: null,
                username: null,
                email: null,
                user_type: null,

            },
            empleadoData: null,
            usersData: null,
            perfiles: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset: 3,
            cargando: false,
            sucursales: [],
            sucursalesData: null,
            empleadoSucursales: [],
            checkSucursal: false,
        },
        components: {
            Multiselect: window.VueMultiselect.default,
        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if (!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + this.offset * 2;
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
        methods: {
            resetData: function() {
                this.empleado.complete_name = null;
                this.empleado.username = null;
                this.empleado.email = null;
                this.empleado.user_type = null;
            },
            getEmpleados: function(page = 1) {
                this.cargaEmpleado = 1;
                var url = "/api/users/get?page=" + page;
                axios.get(url, {
                        params: this.busqueda
                    }).then((response) => {
                        this.empleados = response.data.empleado.data;
                        this.pagination = response.data.pagination;
                        this.cargaEmpleado = 0;
                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },

            createEmpleado: function() {
                this.resetData();
                this.empleadoData = {
                    complete_name: null,
                    username: null,
                    email: null,
                    user_type: null,
                }
                var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editar'));
                myModal.show();
            },

            addEmpleado: function() {
                var url = "/empleados/agregar";
                var data = {
                    empleado: this.empleado,
                    checked: this.checked,
                };
                axios
                    .post(url, data)
                    .then(() => {
                        alert("Nuevo empleado creado con éxito");
                        this.getEmpleados();
                        this.resetData();

                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },
            editEmpleado: function(empleado) {
                var url = "/api/folio/" + empleado.id;
                axios
                    .get(url)
                    .then((response) => {
                        this.empleadoData = response.data;
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editar'));
                        myModal.show();
                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },

            confirmResetPassword: function(empleado) {
                this.empleadoData = empleado;
                console.log(this.empleadoData);
                var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('reset'));
                myModal.show();
            },

            resetPassword: function(empleado) {
                var url = "/api/reestablecer/contrasena";
                axios
                    .post(url, empleado)
                    .then(() => {
                        alert("Contraseña actualizada con exito");
                        alert("Ahora la contraseña por default es 'temporal'");
                        this.getEmpleados();
                        this.resetData();
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('reset'));
                        myModal.hide();
                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },

            updateEmpleado: function() {
                var url = "/api/users/store";
                var data = {
                    empleado: this.empleadoData,
                    username: this.empleadoData.username
                };
                axios
                    .post(url, data)
                    .then(() => {
                        alert("Empleado actualizado con éxito");
                        this.getEmpleados();
                        this.resetData();
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editar'));
                        myModal.hide();
                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },

            removeEmpleado: function(empleado) {
                this.empleadoData = empleado;
                var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('eliminar'));
                myModal.show();
            },

            deleteEmpleado: function() {
                var url = "/api/users/delete/" + this.empleadoData.id;
                var data = {
                    empleado: this.empleadoData,
                };
                axios
                    .get(url, data)
                    .then(() => {
                        alert("Eliminado correctamente");
                        this.getEmpleados();
                        this.resetData();
                        var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('eliminar'));
                        myModal.hide();
                    })
                    .catch(function(error) {
                        alert("Error", "Ha ocurrido un error ");
                        console.log(error);
                    });
            },

            changePage: function(page) {
                this.pagination.current_page = page;
                this.getEmpleados(page);
            },
        },
    });
</script>

@endsection