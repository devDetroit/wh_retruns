var maxSelects = 10;
var selectCount = 1;
const app = new Vue({
    el: "#addCaliper",
    data: {
        fieldToSearch: "",
        upc: "",
        part_number: null,
        caliper: null,
        components: null,
    },

    methods: {
        getWarehouse(warehouse) {
            this.warehouse = warehouse;
        },
        findCaliper() {
            const startWithFilter = ["43", "53", "50", "51"];
            if (this.fieldToSearch.trim().length <= 0) {
                sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
                /* this.clearFields(); */
                return;
            }

            let instance = this;

            axios({
                method: "get",
                url: "/part/get/components/" + instance.fieldToSearch.trim(),
            })
                .then((response) => {
                    if (response.data.state == false) {
                        /*  instance.clearFields(); */
                        /*  caliperComponents.style.display = "block"; */
                        this.components = response.data.components;
                    } else {
                        sweetAlertAutoClose("error", "Caliper ya existe");
                        caliperComponents.style.display = "none";
                    }
                })
                .catch((error) => {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                    console.error(error);
                    /* instance.clearFields(); */
                });
        },
    },
});
