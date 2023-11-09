/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/print-caliper-comp.js ***!
  \********************************************/
var app = new Vue({
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
    serialNumber: null
  },
  methods: {
    getWarehouse: function getWarehouse(warehouse) {
      this.warehouse = warehouse;
    },
    findCaliper: function findCaliper() {
      var _this = this;

      var startWithFilter = ["43", "53", "50", "51"];

      if (this.fieldToSearch.trim().length <= 0) {
        sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
        this.clearFields();
        return;
      }

      var instance = this;
      axios({
        method: "get",
        url: "/part/" + instance.fieldToSearch.trim()
      }).then(function (response) {
        if (response.data.state == false) {
          sweetAlertAutoClose("error", "Caliper no encontrado");
          instance.clearFields();
        } else {
          _this.caliper = response.data.caliper;
          _this.serialNumber = response.data.serialnumber;

          _this.generateCodeBar(response.data.serialnumber);
        }
      })["catch"](function (error) {
        sweetAlertAutoClose("error", "Error procesando la informacion");
        console.error(error);
        instance.clearFields();
      });
    },
    StoreFamily: function StoreFamily() {
      var _this2 = this;

      var url = "/labels/families/store";
      var data = {
        select: this.familyDataSelect,
        input: this.familyDataInput,
        part_number: this.partNumber
      };
      axios.post(url, data).then(function (response) {
        _this2.modal.hide();

        _this2.fieldToSearch = "";
        _this2.partNumber = response.data.part_number;
        _this2.family = response.data.family;
      })["catch"](function (error) {
        console.log(error);
      });
    },
    generateCodeBar: function generateCodeBar(prefix) {
      JsBarcode("#barcode", prefix, {
        displayValue: false,
        width: 2,
        height: 200
      });
    },
    getFamilies: function getFamilies() {
      var instance = this;
      axios({
        method: "get",
        url: "/labels/families/get"
      }).then(function (response) {
        instance.families = response.data;
      })["catch"](function (error) {
        sweetAlertAutoClose("error", "Error procesando la informacion");
      });
    },
    printLabel: function printLabel() {
      var _this3 = this;

      printButton.disabled = true;
      var instance = this;
      var url = "/caliper/print";
      var data = {
        caliper: this.caliper
      };
      axios.post(url, data).then(function () {
        _this3.clearFields();
      })["catch"](function (error) {
        toastr.warning("Error", "Ha ocurrido un error ");
        console.log(error);
      });
    },
    clearFields: function clearFields() {
      this.caliper = null;
      this.fieldToSearch = "";
      this.upc = "";
      this.partNumber = "";
      this.location = "";
      this.family = "";
      this.serialNumber = null;
      document.getElementById("barcode").replaceChildren();
      document.getElementById("scanningInput").focus();
    }
  },
  mounted: function mounted() {},
  computed: {
    warehouseDescription: function warehouseDescription() {
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
    showInputs: function showInputs() {
      return this.warehouse.length <= 0;
    }
  }
});
/******/ })()
;