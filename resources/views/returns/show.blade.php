@extends('bootstrap.layouts.layout')

@php
$canUpdate = Auth::user()->can('update-return');
@endphp

@section('content')

<div id="app">

    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm p-2 bg-body rounded">
                <div class="card-body">
                    <h5 class="card-title text-center">Wharehouse Return Information</h5>
                    <form enctype="multipart/form-data" method="post" action="/returns/{{$return->id}}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control" value="{{ $return->track_number }}" id="trakNumber" name="track_number" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="orderNumber" class="form-label">Order Number</label>
                            <input type="text" class="form-control" value="{{ $return->order_number }}" id="orderNumber" name="order_number" {{ $canUpdate ? '' : 'disabled' }}>
                        </div>
                        <div class="mb-3">
                            <label for="stores" class="form-label">Store</label>
                            <select id="statusSelect" class="form-select" name="store" {{ $canUpdate ? '' : 'disabled' }} required>
                                @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ $return->store_id == $store->id ? 'selected' : ''}}> {{ $store->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Status</label>
                            <select id="statusSelect" class="form-select" name="returnstatus_id" {{ $canUpdate ? '' : 'disabled' }} required>
                                @foreach ($return_status as $status)
                                <option value="{{ $status->id }}" {{ $return->returnstatus_id == $status->id ? 'selected' : ''}}> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>

                        @if($canUpdate)
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="photosModal" tabindex="-1" aria-labelledby="photosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photosModalLabel">Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            @verbatim
                            <div class="carousel-item active" v-for="item in photos">
                                <img :src="'/storage/PartNumbers/' +item.image" class="d-block w-100" alt="">
                            </div>
                            @endverbatim
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <div class="row row-cols-1 row-cols-md-3 g-4">

                @foreach($return->partnumbers as $partnumber)

                <div class="col">
                    <div class="card h-100 shadow-sm p-1 mb-1 bg-body rounded">
                        <div class="card-header">
                            <strong> Part Number:</strong> {{ $partnumber->partnumber }}
                        </div>
                        @if($partnumber->partNumberPhotos()->count() > 0)
                        <a href="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->partNumberPhotos[0]->image}}"> <img src="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->partNumberPhotos[0]->image}}" class="card-img-top" alt="{{$partnumber->partNumberPhotos[0]->image}}"></a>
                        @else
                        <img src="/storage/PartNumbers/noimage.jpg" class="card-img-top">
                        @endif

                        <div class="card-body">
                            <div class="text-end">
                                <h6 class="card-subtitle mb-2 text-muted">Total images {{ $partnumber->partNumberPhotos()->count() }} <i style="cursor: pointer;" class="fa-solid fa-eye" v-on:click="getPhotos({{ $partnumber->id }})"></i></h6>
                            </div>
                            <h5 class="card-title">Notes:</h5>
                            <p class="card-text">{{ $partnumber->note ?? 'No notes available' }}</p>
                        </div>
                        <div class="card-footer">
                            <strong> Status:</strong> {{ $partnumber->status->description }}
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </div>
    </div>



    @endsection

    @section('scripts')

    <script>
        new Vue({
            el: '#app',
            data: {
                photosModal: new bootstrap.Modal(document.getElementById('photosModal')),
                photos: []
            },
            methods: {

                getPhotos(partnumber_id) {
                    let instance = this;
                    this.photos = [];
                    axios({
                            method: 'get',
                            url: '/api/photos',
                            params: {
                                partNumber_id: partnumber_id
                            }
                        })
                        .then(function(response) {
                            instance.photos = response.data;
                        }).then(() => {
                            if (instance.photos.lenth > 0)
                                instance.photosModal.show();
                            else
                                sweetAlertAutoClose('error', "no photos to show")
                        }).catch(error => sweetAlertAutoClose('error', "no photos to show"));
                }
            },
            mounted() {

            },
        })
    </script>

    @endsection