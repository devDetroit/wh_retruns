const app = new Vue({
    el: "#checked",
    created: function () {
        this.getLastRecords();
    },
    data: {
        fieldToSearch: "",
        upc: "",
        family: "",
        partNumber: "",
        location: "",
        warehouse: "",
        newWindow: null,
        families: [],
        familyDataSelect: null,
        familyDataInput: null,
        createOrSelect: true,
        modal: null,
        part_number: null,
        part: null,
        serialNumber: null,
        lasts: null,
    },

    methods: {
        getWarehouse(warehouse) {
            this.warehouse = warehouse;
        },

        getLastRecords: function () {
            var url = "/part/get/last";
            axios
                .get(url)
                .then((response) => {
                    this.lasts = response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        findCaliper() {
            this.disableButtons()
            if (this.fieldToSearch.trim().length <= 0) {
                sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
                this.clearFields();
                this.enableButtons()
                return;
            }

            let instance = this;
            axios({
                method: "get",
                url: "/checked/" + instance.fieldToSearch.trim(),
            })
                .then((response) => {
                    if (response.data.state == 0) {
                        sweetAlertAutoClose("error", response.data.message);
                        instance.clearFields();
                    } else if (response.data.state == 1) {
                        sweetAlertAutoClose("error", response.data.message);
                        instance.clearFields();
                    } else {
                        instance.part = response.data.part;

                    }
                    this.enableButtons()

                }).then(() => {
                    if (instance.part != null)
                        instance.saveComponents()
                })
                .catch((error) => {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                    console.error(error);
                    instance.clearFields();
                    this.enableButtons()
                });
        },
        disableButtons() {
            scanningInput.disabled = true
            cleanButton.disabled = true
            if (document.getElementById('submitButton'))
                submitButton.disabled = true
        },
        enableButtons() {
            scanningInput.disabled = false
            cleanButton.disabled = false
            if (document.getElementById('submitButton'))
                submitButton.disabled = false
            scanningInput.focus()
        },
        saveComponents() {
            this.disableButtons()
            var _this2 = this;
            var url = "/checked/part/store";
            var data = {
                part: this.part,
            };
            axios
                .post(url, data)
                .then(function (response) {
                    _this2.part = null;
                    _this2.fieldToSearch = "";
                    sweetAlertAutoClose(
                        "success",
                        "Parte recibida exitosamente"
                    );
                    _this2.enableButtons()
                }).then(() => _this2.getLastRecords())
                .catch(function (error) {
                    console.log(error);
                    _this2.enableButtons()
                });
        },
        clearFields() {
            this.part = null;
            this.fieldToSearch = "";
            this.upc = "";
            this.partNumber = "";
            this.location = "";
            this.family = "";
            this.serialNumber = null;
        },
    },
    mounted() { scanningInput.focus() },
    computed: {
        warehouseDescription: function () {
            var description = "";
            switch (this.warehouse) {
                case "jrz":
                    description = "REMAN";
                    break;

                case "elp":
                    description = "ELP WH";
                    break;

                default:
                    description = "";
                    break;
            }
            return description;
        },
        showInputs: function () {
            return this.warehouse.length <= 0;
        },
    },
});
