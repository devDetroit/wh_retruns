var maxSelects = 10;
var selectCount = 1;

    function addSelect() {
        if (selectCount >= maxSelects) {
            alert('Has alcanzado el maximo de componentes permitidos.');
            return;
        }

        var originalSelect = document.getElementById('component-dropdown');
        var cloneSelect = originalSelect.cloneNode(true);

        var newId = 'component-dropdown-' + (selectCount + 1);
        cloneSelect.id = newId;
        cloneSelect.name = newId;

        var additionalSelects = document.getElementById('additional-selects');

        var deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn btn-danger';
        deleteButton.textContent = 'Eliminar';
        deleteButton.addEventListener('click', function() {
            additionalSelects.removeChild(deleteButton);
            additionalSelects.removeChild(document.querySelector('br.before'));
            additionalSelects.removeChild(cloneSelect);
            additionalSelects.removeChild(document.querySelector('br.after'));
            selectCount--;
        });

        additionalSelects.appendChild(cloneSelect);
        additionalSelects.appendChild(document.createElement('br')).className = 'before';
        additionalSelects.appendChild(deleteButton);
        additionalSelects.appendChild(document.createElement('br')).className = 'after';

        selectCount++;
    }

    document.getElementById('add-select').addEventListener('click', addSelect);

const app = new Vue({
    el: "#addCaliper",
    data: {
        fieldToSearch: ""
    },

    methods: {
        getWarehouse(warehouse) {
            this.warehouse = warehouse;
        },
        findCaliper() {
            const startWithFilter = ["43", "53", "50", "51"];
            if (this.fieldToSearch.trim().length <= 0) {
                sweetAlertAutoClose("warning", "No se escaneo ni un caliper");
                this.clearFields();
                return;
            }

            let instance = this;
            axios({
                method: "get",
                url: "/caliper/" + instance.fieldToSearch.trim(),
            })
                .then((response) => {
                    if (response.data.state == false) {
                        sweetAlertAutoClose("error", "Caliper no encontrado");
                        instance.clearFields();
                    } else {
                        this.caliper = response.data.caliper;
                    }
                })
                .catch((error) => {
                    sweetAlertAutoClose(
                        "error",
                        "Error procesando la informacion"
                    );
                    console.error(error);
                    instance.clearFields();
                });
        }
    },
});
