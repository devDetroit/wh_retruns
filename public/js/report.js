const app = new Vue({
    el: "#report",
    mounted() {
        this.initializeTabulator();
    },
    created: function () {
        this.getTypes();
    },
    data: {
        busqueda: {
            fechai: null,
            fechaf: null,
            type: null,
        },
        parttypes: null,
        table: null,
    },

    methods: {
        getTypes: function (page) {
            var url = "/get/part/types";
            axios
                .get(url)
                .then((response) => {
                    this.parttypes = response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },
        getRecords() {
            this.initializeTabulator();
        },
        downloadData() {
            this.table.download("csv", "data.csv");
        },
        initializeTabulator() {
            let ins = this;
            this.table = new Tabulator("#reportes", {
                height: 450,
                pagination: true, //enable.
                paginationSize: 50,
                ajaxURL: "/api/report/get/data",
                ajaxConfig: "POST", //ajax HTTP request type
                ajaxParams: {
                    // POST parameters to send
                    busqueda: this.busqueda,
                    // Add more parameters as needed
                },
                layout: "fitColumns",
                columns: [
                    {
                        title: "Tipo de parte",
                        field: "part.parttype.part_description",
                        headerFilter: true,
                    },
                    {
                        title: "Estatus",
                        field: "status",
                        headerFilter: true,
                    },
                    {
                        title: "Numero serial",
                        field: "serial_number",
                        headerFilter: true,
                    },
                    {
                        title: "Fecha de recibido",
                        field: "received_date",
                        headerFilter: true,
                    },
                ],
            });
        },
    },
});
