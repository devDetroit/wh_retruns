
import SearchTracking from "./search-component";

var viewBtn = function (cell, formatterParams, onRendered) { //plain text value
    return '<button type="button" class="btn btn-sm btn-primary">Details</button>';
};

var deleteBtn = function (cell, formatterParams, onRendered) { //plain text value
    return '<button type="button" class="btn btn-sm btn-danger">Delete</button>';
};
let getCUrrentTime = function () {
    var date = new Date();
    app.updateDate = date.toLocaleString();
}
const app = new Vue({
    el: '#indexapp',
    components: {
        'search-tracking': SearchTracking
    },
    data: {
        table: null,
        trackNumber: '',
        status: '',
        updateDate: null
    },
    methods: {
        onDelete(data) {
            let deleteConfirmation = confirm('are you sure to delete this record?');

            if (!deleteConfirmation)
                return;

            let inst = this;

            axios.post(`/returns/${data.id}`)
                .then(function (response) {
                    sweetAlertAutoClose('success', response.data.message);
                    inst.table.setData();
                })
                .catch(function (error) {
                    alert('something went wrong')
                    console.error(error)
                });

        },
        isAllowed() {
            return document.getElementById('btnUserType').value == 'admin';
        },
        initializeTabulator() {

            let ins = this;
            let isAllowedToDelete = this.isAllowed();
            this.table = new Tabulator("#returns-table", {
                height: '700',
                pagination: true, //enable.
                paginationSize: 50,
                ajaxURL: '/api/returns',
                layout: "fitColumns",
                columns: [{
                    title: "tracking number",
                    field: "track_number",
                    headerFilter: true
                },
                {
                    title: "order number",
                    field: "order_number",
                    headerFilter: true

                },
                {
                    title: "status",
                    field: "description",
                    headerFilter: true
                },
                {
                    title: "store",
                    field: "name",
                    headerFilter: true
                },
                {
                    title: "created by",
                    field: "createdBy",
                    headerFilter: true
                },
                {
                    title: "created At",
                    field: "created_at",
                    headerFilter: true
                },
                {
                    title: "updated by",
                    field: "updatedBy",
                    headerFilter: true
                },
                {
                    title: "updated At",
                    field: "updated_at",
                    headerFilter: true
                },
                {
                    hozAlign: "center",
                    formatter: viewBtn,
                    cellClick: (e, cell) => {
                        var currentData = cell.getRow().getData();
                        location.href = 'returns/' + currentData.id;
                    }
                },
                {
                    hozAlign: "center",
                    formatter: deleteBtn,
                    visible: isAllowedToDelete,
                    cellClick: (e, cell) => {
                        var currentData = cell.getRow().getData();
                        ins.onDelete(currentData);
                    }
                },
                ],
            });
        }
    },
    mounted() {
        this.initializeTabulator();

        var date = new Date();
        this.updateDate = date.toLocaleString();

        setInterval(() => {
            if (this.table != null) {
                this.table.setData().then(getCUrrentTime);
            }
        }, 1500000);
    },
})
