new Vue({
    el: "#empleado",
    created: function () {
        this.getEmpleados();
        this.obtenerPerfiles();

        console.log('jahsdjas');
    },
    data: {
        checked: false,
        cargaEmpleado: 0,
        busqueda: {
            codigo: '',
            nombrecompleto: '',
            empresa: '',
            perfil: '',
            departamento: '',
            sucursal: '',
            /* estadoempleado: '', */
        },
        empleados: [],
        usuario: {
            email: null,
            idioma: null,
        },
        empleado: {
            /////// Datos personales
            cUsuarioFolio: null,
            cUsuarioCodigo: null,
            cUsuarioNombre: null,
            cUsuarioApellidoP: null,
            cUsuarioApellidoM: null,
            cUsuarioNombreCompleto: null,
            cUsuarioFechNac: null,
            cUsuarioLugarNac: null,
            cUsuarioEstadoCivil: null,
            cUsuarioSexo: null,
            cUsuarioCorreoPersonal: null,
            cUsuarioTelefono: null,
            cUsuarioExpediente: null,
            cUsuarioCodigoPostal: null,
            cUsuarioDireccion: null,
            cUsuarioPoblacion: null,
            cUsuarioEstado: null,
            cUsuarioNombrePadre: null,
            cUsuarioNombreMadre: null,
            cUsuarioEntidadFederativa: null,
            ////// Datos administrativos
            cUsuarioCURPI: null,
            cUsuarioCURPF: null,
            cUsuarioPerfil: null,
            cUsuarioNumSeguro: null,
            cUsuarioUMF: null,
            cUsuarioRFC: null,
            cUsuarioHomoclave: null,
            cUsuarioEstadoEmpleado: null,
            cUsuarioFechaAlta: null,
            cUsuarioNumeroAfore: null,
            cUsuarioFechaBaja: null,
            cUsuarioCausaBaja: null,
            cUsuarioActivo: null,
            cUsuarioFechaReingreso: null,
            cUsuarioCorreoInterno: null,
            cUsuarioSubcontratacion: null,
            cUsuarioExtranjeroSinCURP: null,
            cUsuarioDiasVacTomadasAntesdeAlta: null,
            cUsuarioDiasPrimaVacTomadasAntesdeAlta: null,
            /*   cUsuarioPass: null,  */
            /*  cUsuarioLogin: null,  */
            /*   remember_token: null,  */
            cUsuarioIdioma: null,
            //// Externos
            /*   cUsurioReiniciar: null,  */
            cUsuarioSincro: null,
            /* cUsuarioDepartamento: null,  */
            cUsuarioPassLocal: null,
            cUsuarioClaveLocal: null,
            cUsuarioCovid: null,

            /////Extras
            cUsuarioCampoExtra1: null,
            cUsuarioCampoExtra2: null,
            cUsuarioCampoExtra3: null,
            cUsuarioCampoExtraNum1: null,
            cUsuarioCampoExtraNum2: null,
            cUsuarioCampoExtraNum3: null,
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
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
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
        resetData: function () {
            this.empleado.cUsuarioFolio = null;
            this.empleado.cUsuarioCodigo = null;
            this.empleado.cUsuarioNombre = null;
            this.empleado.cUsuarioApellidoP = null;
            this.empleado.cUsuarioApellidoM = null;
            this.empleado.cUsuarioNombreCompleto = null;
            this.empleado.cUsuarioActivo = null;
            this.empleado.cUsuarioFechNac = null;
            this.empleado.cUsuarioLugarNac = null;
            this.empleado.cUsuarioEstadoCivil = null;
            this.empleado.cUsuarioSexo = null;
            this.empleado.cUsuarioCURPI = null;
            this.empleado.cUsuarioCURPF = null;
            this.empleado.cUsuarioPerfil = null;
            this.empleado.cUsuarioNumSeguro = null;
            this.empleado.cUsuarioUMF = null;
            this.empleado.cUsuarioRFC = null;
            this.empleado.cUsuarioHomoclave = null;
            this.empleado.cUsuarioEstadoEmpleado = null;
            this.empleado.cUsuarioFechaAlta = null;
            this.empleado.cUsuarioTelefono = null;
            this.empleado.cUsuarioExpediente = null;
            this.empleado.cUsuarioCodigoPostal = null;
            this.empleado.cUsuarioDireccion = null;
            this.empleado.cUsuarioPoblacion = null;
            this.empleado.cUsuarioEstado = null;
            this.empleado.cUsuarioNombrePadre = null;
            this.empleado.cUsuarioNombreMadre = null;
            this.empleado.cUsuarioNumeroAfore = null;
            this.empleado.cUsuarioFechaBaja = null;
            this.empleado.cUsuarioCausaBaja = null;
            this.empleado.cUsuarioCampoExtra1 = null;
            this.empleado.cUsuarioCampoExtra2 = null;
            this.empleado.cUsuarioCampoExtra3 = null;
            this.empleado.cUsuarioCampoExtraNum1 = null;
            this.empleado.cUsuarioCampoExtraNum2 = null;
            this.empleado.cUsuarioCampoExtraNum3 = null;
            this.empleado.cUsuarioFechaReingreso = null;
            this.empleado.cUsuarioCorreoInterno = null;
            this.empleado.cUsuarioCorreoPersonal = null;
            this.empleado.cUsuarioEntidadFederativa = null;
            this.empleado.cUsuarioSubcontratacion = null;
            this.empleado.cUsuarioExtranjeroSinCURP = null;
            this.empleado.cUsuarioDiasVacTomadasAntesdeAlta = null;
            this.empleado.cUsuarioDiasPrimaVacTomadasAntesdeAlta = null;
            /*  this.empleado.cUsuarioPass = null;  */
            /*  this.empleado.cUsuarioLogin = null;  */
            /*      this.empleado.remember_token = null;  */
            /*     this.empleado.cUsuarioReiniciar = null;  */
            this.empleado.cUsuarioSincro = null;
            /*   this.empleado.cUsuarioDepartamento = null;  */
            this.empleado.cUsuarioPassLocal = null;
            this.empleado.cUsuarioClaveLocal = null;
            this.empleado.cUsuarioCovid = null;
        },

        getEmpleados: function (page = 1) {
            this.cargaEmpleado = 1;
            var url = "/empleados/obtener?page=" + page;
            axios
                .get(url, { params: this.busqueda })
                .then((response) => {
                    this.empleados = response.data.empleado.data;
                    this.pagination = response.data.pagination;
                    this.cargaEmpleado = 0;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        createEmpleado: function () {
            this.resetData();
            $("#agregar").modal("show");
        },

        addEmpleado: function () {
            var url = "/empleados/agregar";
            var data = {
                empleado: this.empleado,
                checked: this.checked,
            };
            axios
                .post(url, data)
                .then(() => {
                    toastr.success("Nuevo empleado creado con éxito");
                    this.getEmpleados();
                    this.resetData();
                    $("#agregar").modal("hide");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        verificatExistenciaEnUsers: function (id) {
            var url = "/empleados/verificar/users/" + id;
            axios
                .get(url)
                .then((response) => {
                    this.usersData = response.data;
                    $("#editar").modal("show");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        editEmpleado: function (empleado) {
            var url = "/empleados/folio/" + empleado.cUsuarioFolio;
            this.verificatExistenciaEnUsers(empleado.cUsuarioFolio);
            axios
                .get(url)
                .then((response) => {
                    this.empleadoData = response.data;
                    $("#editar").modal("show");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        confirmResetPassword: function (empleado) {
            this.empleadoData = empleado;
            console.log(this.empleadoData);
            $("#reset").modal("show");
        },

        resetPassword: function (empleado) {
            var url = "/empleados/reestablecer/contrasena";
            axios
                .post(url, empleado)
                .then(() => {
                    toastr.success("Contraseña actualizada con exito");
                    toastr.success("Ahora la contraseña por default es 'temporal'");
                    this.getEmpleados();
                    this.resetData();
                    $("#reset").modal("hide");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        updateEmpleado: function () {
            var url = "/empleados/actualizar";
            var data = {
                empleado: this.empleadoData,
                usuario: this.usersData,
                crearUsuario: this.usuario,
                checked: this.checked,
            };
            axios
                .post(url, data)
                .then(() => {
                    toastr.success("Empleado actualizado con éxito");
                    this.getEmpleados();
                    this.resetData();
                    $("#editar").modal("hide");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        removeEmpleado: function (empleado) {
            this.empleadoData = empleado;
            $("#eliminar").modal("show");
        },

        deleteEmpleado: function () {
            var url = "/empleados/eliminar";
            var data = {
                empleado: this.empleadoData,
            };
            axios
                .post(url, data)
                .then(() => {
                    toastr.success("Eliminado correctamente");
                    this.getEmpleados();
                    this.resetData();
                    $("#eliminar").modal("hide");
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getEmpleados(page);
        },

        obtenerPerfiles: function () {
            var url = "/perfiles/todo";
            axios
                .get(url)
                .then((response) => {
                    this.perfiles = response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        getSucursales: function () {
            var url = "/sucursales/obtener";
            axios.get(url).then((response) => {
                this.sucursales = response.data;
            }).catch(function (error) {
                toastr.warning("Ha ocurrido un error al obtener sucursales");
                console.log(error);
            });
        },

        onChangeSucursal: function () {
            if (this.checkSucursal) {
                this.sucursalesData = this.sucursales;
            } else {
                this.sucursalesData = [];
            }
        },

        getEmpleadoSucursales: function () {
            var id = this.empleadoData.cUsuarioFolio;
            var url = `/empleados/sucursal/obtener/${id}`;

            this.cargando = true;
            axios.get(url).then((response) => {
                this.empleadoSucursales = response.data;
                this.sucursalesData = this.empleadoSucursales;
                this.cargando = false;
            })
                .catch(function (error) {
                    toastr.warning("Error al obtener sucursales del empleado");
                    console.log(error);
                });
        },

        agregarSucursales: function (empleado) {
            // console.log(empleado);
            this.empleadoData = empleado;
            this.getSucursales();
            this.getEmpleadoSucursales();
            $("#agregarSucursal").modal("show");
        },

        addSucursales: function () {
            var url = "/empleados/sucursal/agregar";
            var data = {
                'sucursales': this.sucursalesData,
                'empleado': this.empleadoData,
            };

            axios.post(url, data).then(() => {
                toastr.success("Sucursales asignadas con éxito");
                this.sucursalesData = [];
                this.checkSucursal = false;
                this.getEmpleadoSucursales();
            })
                .catch(function (error) {
                    toastr.warning("Ha ocurrido un error al asignar sucursales");
                    console.log(error);
                });
        },

        deleteSucursal: function (sucursal) {
            var id = sucursal.cSucursalFolio;
            var url = `/empleados/sucursal/eliminar/${this.empleadoData.cUsuarioFolio}/${id}`;

            axios.post(url).then(() => {
                toastr.success("Eliminado correctamente");
                this.sucursalesData = [];
                this.checkSucursal = false;
                this.getEmpleadoSucursales();
            })
                .catch(function (error) {
                    toastr.warning("Ha ocurrido un error al borrar sucursal");
                    console.log(error);
                });
        },
    },
});
