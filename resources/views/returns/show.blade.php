@extends('bootstrap.layouts.layout')

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
                            <input type="text" class="form-control" value="{{ $return->track_number }}" id="orderNumber" name="order_number">
                        </div>
                        <div class="mb-3">
                            <label for="trakNumber" class="form-label">Status</label>
                            <select id="statusSelect" class="form-select" name="returnstatus_id" required>
                                @foreach ($return_status as $status)
                                <option value="{{ $status->id }}" {{ $return->returnstatus_id == $status->id ? 'selected' : ''}}> {{ $status->description }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </form>
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
                        @if(isset($partnumber->image))
                        <a href="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}"> <img src="/storage/PartNumbers/{{$partnumber->returns_id}}-{{$partnumber->image}}" class="card-img-top" alt="{{$partnumber->image}}"></a>
                        @else
                        <img src="/storage/PartNumbers/noimage.jpg" class="card-img-top">
                        @endif

                        <div class="card-body">
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

    @if (session('status'))
    <script>
        let timerInterval;
        let message = '<?= session('status') ?>';
        Swal.fire({
            icon: 'success',
            title: message,
            showConfirmButton: false,
            timer: 1500,
            willClose: () => {
                clearInterval(timerInterval)
            }
        });
    </script>
    @endif
    @endsection