@extends('bootstrap.layouts.layout')

@section('content')
<div id="app">

    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm p-2 bg-body rounded">
                <div class="card-body">
                    <h5 class="card-title text-center">Create Return</h5>
                    <form enctype="multipart/form-data" method="post" @submit.prevent="onSubmit">
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" id="trakNumber" name="track_number" v-model="track_number" required>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary" v-on:click="cleanAllForms">New Return</button>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row justify-content-md-center mt-4">
        <div class="col-md-10">
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#partNumberModal">Add Part Number</button>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Part Number</th>
                        <th scope="col">Status</th>
                        <th scope="col">Note</th>
                        <th scope="col">Image Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in partNumberslist" :key="index">
                        <th scope="row">@{{ index+1 }}</th>
                        <td>@{{ item.partnumber }}</td>
                        <td>@{{ item.statusDescription}}</td>
                        <td>@{{ item.note}}</td>
                        <td>@{{ item.image}}</td>
                        <td class="text-center"><button type="button" class="btn btn-sm btn-danger" v-on:click="onDeletePartNumber(index)">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="partNumberModal" tabindex="-1" aria-labelledby="partNumberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="partNumberModalLabel">Add Part Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="partNumberForm" enctype="multipart/form-data" method="post" @submit.prevent="onSubmitPartNumber">
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Part Number</label>
                            <input type="text" class="form-control" id="trakNumber" v-model="partNumber.partnumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="imageFile">
                        </div>
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Status</label>
                            <select id="statusSelect" class="form-select" v-on:change="setStatusDescription" required v-model="partNumber.status_id">
                                @foreach ($statuses as $status)
                                <option value="{{ $status->id }}"> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" rows="3" v-model="partNumber.note"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary" v-on:click="cleanObject">New</button>
                            <button type="submit" class="btn btn-sm btn-primary">+Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


@section('scripts')
<script src="https://unpkg.com/vue@3"></script>
<script>
    Vue.createApp({
        data() {
            return {
                partNumber: {
                    partnumber: '',
                    image: '',
                    status_id: '',
                    statusDescription: '',
                    note: '',
                    index: 0
                },
                track_number: '',
                formData: new FormData(),
                partNumberslist: [],
                isValid: false,
                //myModal: new bootstrap.Modal(document.getElementById('myModal'))
            }
        },
        methods: {
            onDeletePartNumber(index) {
                this.partNumberslist.splice(index, 1);
            },
            onSubmitPartNumber() {

                if (this.partNumberslist.length > 0) {
                    const result = this.partNumberslist.find(({
                        partnumber
                    }) => partnumber === this.partNumber.partnumber);

                    if (result !== undefined) {
                        new sweetAlert('error', 'part number already exists');
                        return;
                    }
                }
                if (imageFile.files.length > 0) {
                    var extension = imageFile.files[0].name.split('.').pop();
                    this.formData.append(`images${this.partNumber.index}`, imageFile.files[0]);

                    this.partNumber.image = imageFile.files[0].name;
                }
                var tmpPartNumber = {
                    ...this.partNumber
                }

                this.partNumberslist.push(tmpPartNumber);
                sweetAlertAutoClose('success', 'PartNumber added');
                this.cleanObject();
                this.partNumber.index += 1;
            },
            setStatusDescription() {
                var sele = document.getElementById('statusSelect');
                this.partNumber.statusDescription = sele.options[sele.selectedIndex].text;
            },
            cleanObject() {
                for (const key in this.partNumber) {
                    if (key == 'index')
                        continue;

                    this.partNumber[key] = '';
                }
                document.getElementById("partNumberForm").reset();
            },
            cleanAllForms() {
                this.cleanObject();
                this.partNumberslist = [];
                this.track_number = '';
                this.formData = new FormData();
            },
            onSubmit() {

                if (!this.validateSubmit())
                    return;


                var data = {
                    ...this.partNumberslist
                }

                data.track_number = this.track_number;
                let instance = this;

                axios.post('/returns/store', data)
                    .then(function(response) {
                        instance.submitFiles(response.data.returnValue);
                        instance.cleanAllForms();
                        sweetAlertAutoClose('success', response.data.message);
                    })
                    .catch(function(error) {
                        sweetAlert('error', error.response.data?.errors?.track_number[0]);
                    });

                // this.myModal.hide();

            },
            submitFiles(rid) {
                this.formData.append('return_id', rid);
                this.formData.append('totalImages', this.partNumber.index);

                axios.post('/returns/files', this.formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            },
            validateSubmit() {
                if (this.track_number.length <= 0) {
                    new sweetAlert('warning', 'plase add tracking number');
                    return false;
                }

                if (this.partNumberslist.length <= 0) {
                    new sweetAlert('warning', 'plase add part numbers');
                    return false;
                }

                return true;
            }
        },
    }).mount('#app')
</script>


@endsection