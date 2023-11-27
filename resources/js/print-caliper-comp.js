const app = new Vue({
    el: "#printLabelApp",
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
        caliper: null,
        serialNumber: null,
    },

    methods: {
        getWarehouse(warehouse) {
            this.warehouse = warehouse;
        },
        disableButtons() {
            scanningInput.disabled = true
            cleanButton.disabled = true
            if (document.getElementById('printButton'))
                printButton.disabled = true
        },
        enableButtons() {
            scanningInput.disabled = false
            cleanButton.disabled = false
            if (document.getElementById('printButton'))
                printButton.disabled = false
        },
        findCaliper() {
            this.disableButtons()
            const startWithFilter = ["43", "53", "50", "51"];
            if (this.fieldToSearch.trim().length <= 0) {
                sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
                this.clearFields();
                return;
            }

            let instance = this;
            axios({
                method: "get",
                url: "/part/" + instance.fieldToSearch.trim(),
            })
                .then((response) => {
                    if (response.data.state == false) {
                        sweetAlertAutoClose("error", "Caliper no encontrado");
                        instance.clearFields();
                    } else {
                        this.caliper = response.data.caliper;
                        this.serialNumber = response.data.serialnumber;
                        this.generateCodeBar(response.data.serialnumber);
                    }
                    instance.enableButtons()
                })
                .catch((error) => {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                    console.error(error);
                    instance.clearFields();
                    instance.enableButtons()
                });
        },
        StoreFamily() {
            var url = "/labels/families/store";
            var data = {
                select: this.familyDataSelect,
                input: this.familyDataInput,
                part_number: this.partNumber,
            };
            axios
                .post(url, data)
                .then((response) => {
                    this.modal.hide();
                    this.fieldToSearch = "";
                    this.partNumber = response.data.part_number;
                    this.family = response.data.family;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        generateCodeBar(prefix) {
            JsBarcode("#barcode", prefix, {
                displayValue: false,
                width: 2,
                height: 200,
            });
        },
        getFamilies() {
            let instance = this;
            axios({
                method: "get",
                url: "/labels/families/get",
            })
                .then(function (response) {
                    instance.families = response.data;
                })
                .catch((error) => {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                });
        },
        printLabel() {
            let instance = this;
            instance.disableButtons()
            var url = "/caliper/print";
            var data = {
                caliper: this.caliper,
            };
            axios
                .post(url, data)
                .then(() => {
                    this.clearFields();
                    instance.enableButtons()
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                    instance.enableButtons()
                });
        },
        clearFields() {
            this.caliper = null;
            this.fieldToSearch = "";
            this.upc = "";
            this.partNumber = "";
            this.location = "";
            this.family = "";
            this.serialNumber = null
            document.getElementById("barcode").replaceChildren();
            document.getElementById("scanningInput").focus();
        },
    },
    mounted() { },
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
