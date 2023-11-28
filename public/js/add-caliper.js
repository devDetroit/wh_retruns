var maxSelects = 10;
var selectCount = 1;
const app = new Vue({
    el: "#addCaliper",
    created: function () {
        this.getTypes();
        this.getFamilies();
    },
    data: {
        search: false,
        fieldToSearch: "",
        upc: "",
        part_number: null,
        caliper: null,
        components: null,
        family: null,
        types: [],
        typeSelected: null,
        families: [],
        familySelected: null,
        parts: [],
        part: null,
        test: null,
        visualizeStatus: false,
        serial: null,
        partTypeStatus: false,
        familyStatus: false,
        partAlreadyExists: false,
        isRequired: true,
        disablePrintButton: false,
    },
    components: {
        Multiselect: window.VueMultiselect.default,
    },
    methods: {
        resetAllVariables: function () {
            (this.search = false),
                (this.fieldToSearch = ""),
                (this.upc = ""),
                (this.part_number = null),
                (this.caliper = null),
                (this.components = null),
                (this.family = null),
                (this.typeSelected = null),
                (this.familySelected = null),
                (this.parts = []),
                (this.part = null),
                (this.test = null),
                (this.visualizeStatus = false),
                (this.serial = null),
                (this.partTypeStatus = false),
                (this.familyStatus = false),
                (this.partAlreadyExists = false);
            this.disablePrintButton = false;
        },
        getPartsOfExistentPart: function (id) {
            var url = "/part/get/" + id;
            axios
                .get(url)
                .then((response) => {
                    this.parts = response.data.components;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },
        storePartDetails: function (partSaved) {
            var url = "/part/details/store";
            var data = {
                part: partSaved,
                serial: this.serial,
                components: this.parts,
            };
            return axios
                .post(url, data)
                .then((response) => {
                    return response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        onTypePartchange: function (id) {
            var url = "/get/components/types/" + id;
            axios
                .get(url)
                .then((response) => {
                    if (id == 1) {
                        this.components = response.data;
                    } else {
                        this.parts = response.data;
                        this.partTypeStatus = true;
                    }
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        storePart: function () {
            var url = "/part/store";
            var data = {
                part_number: this.fieldToSearch,
                type: this.typeSelected,
                family: this.familySelected,
                components: this.parts,
            };
            return axios
                .post(url, data)
                .then((response) => {
                    return response.data; // Return the data to be used in subsequent calls if needed
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },

        printLabel: function printLabel() {
            var self = this; // Store the reference to 'this' in a variable
            self.disablePrintButton = true;
            const isQuantityNotInRange = self.parts.some((part) => {
                return part.quantity < part.min || part.quantity > part.max;
            });

            if (isQuantityNotInRange) {
                sweetAlertAutoClose(
                    "error",
                    "There is at least one quantity value outside the specified range. Please verify."
                );
            } else {
                this.generateSerial();
                this.storePart().then(function (partSavedData) {
                    self.storePartDetails(partSavedData).then(function (
                        partDetailed
                    ) {
                        var url = "/part/print";
                        var data = {
                            part: partSavedData,
                            serial: self.serial,
                            type: self.typeSelected,
                            family: self.familySelected,
                            components: self.parts,
                        };
                        axios
                            .post(url, data)
                            .then(function () {
                                /*   self.resetAllVariables();  */ // Use 'self' to call resetAllVariables
                                sweetAlertAutoClose(
                                    "success",
                                    "Etiqueta impresa exitosamente"
                                );
                                self.disablePrintButton = false;
                            })
                            .catch(function (error) {
                                console.log(error);
                            });
                    });
                });
            }
        },
        generateCodeBar: function generateCodeBar(prefix) {
            this.serial = prefix;
            JsBarcode("#barcode", prefix, {
                displayValue: false,
                width: 2,
                height: 200,
            });
            this.visualizeStatus = true;
        },
        generateSerial: function generateSerial() {
            var _this = this;
            if (this.fieldToSearch.trim().length <= 0) {
                sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
                return;
            }

            var instance = this;
            axios({
                method: "get",
                url:
                    "/part/add/find/" +
                    instance.fieldToSearch.trim() +
                    "/" +
                    instance.typeSelected.id,
            })
                .then(function (response) {
                    _this.generateCodeBar(response.data);
                })
                ["catch"](function (error) {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                    console.error(error);
                    instance.clearFields();
                });
        },
        getTypes: function () {
            var url = "/get/part/types";
            axios
                .get(url)
                .then((response) => {
                    this.types = response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },
        getFamilies: function () {
            var url = "/get/families";
            axios
                .get(url)
                .then((response) => {
                    this.families = response.data;
                })
                .catch(function (error) {
                    toastr.warning("Error", "Ha ocurrido un error ");
                    console.log(error);
                });
        },
        addComponent() {
            const isEmptyComponent = this.parts.some(
                (part) => Object.keys(part.component).length === 0
            );

            if (isEmptyComponent) {
                sweetAlertAutoClose(
                    "error",
                    "Existe algun componente vacio, verifique por favor"
                );
            } else {
                this.parts.push({ component: null, quantity: 0 });
                this.partTypeStatus = true;
            }
        },
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
                url: "/part/verify/" + instance.fieldToSearch.trim(),
            })
                .then((response) => {
                    if (response.data.state == false) {
                        sweetAlertAutoClose(
                            "success",
                            "Producto no existe, puedes crearlo"
                        );
                        instance.search = true;
                    } else {
                        if (response.data.type.id == 1) {
                            instance.search = true;
                            instance.typeSelected = response.data.type;
                            instance.familySelected = response.data.family;
                            instance.familyStatus = true;
                            instance.partTypeStatus = true;
                            instance.part = response.data.part;
                            this.onTypePartchange(1);
                            this.getPartsOfExistentPart(instance.part.id_part);
                            instance.partAlreadyExists = response.data.exists;
                        } else if (response.data.type.id == 2) {
                            instance.search = true;
                            instance.typeSelected = response.data.type;
                            instance.familySelected = response.data.family;
                            instance.familyStatus = true;
                            instance.partTypeStatus = true;
                            instance.part = response.data.part;
                            this.onTypePartchange(2);
                            instance.partAlreadyExists = response.data.exists;
                        }
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
