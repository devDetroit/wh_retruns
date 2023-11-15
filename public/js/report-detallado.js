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
                ajaxURL: "/report/detallado/data",
                ajaxConfig: "get", //ajax HTTP request type
                ajaxParams: {
                    // POST parameters to send
                    busqueda: this.busqueda,
                    // Add more parameters as needed
                },
                layout: "fitColumns",
                columns: [
                    {
                        title: "Serial",
                        field: "serial_number",
                        headerFilter: true,
                    },
                    {
                        title: "Family",
                        field: "description",
                        headerFilter: true,
                    },
                    {
                        title: "Caliper",
                        field: "part_num",
                        headerFilter: true,
                    },
                    {
                        title: "Component",
                        field: "component",
                        headerFilter: true,
                    },
                    {
                        title: "Qty",
                        field: "quantity",
                    },
                    {
                        title: "PrintedAt",
                        field: "created_at",
                    },
                    {
                        title: "ReceivedAt",
                        field: "received_date",
                    },
                ],
            });
        },
    },
});
