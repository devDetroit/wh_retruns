@extends('bootstrap.layouts.layout')

@section('content')

<div id="indexapp">
    <div class="row mt-4">
        <div class="col-md-4">
            <strong>Last Updated At:</strong> @{{ updateDate }}
        </div>
        <div class="col-md-4 ms-auto">
            <search-tracking></search-tracking>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-list-ol"></i> Total records:<strong> {{ $totalRecords }}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-check"></i> Total Done: <strong> {{ isset($counters[2]) ? $counters[2]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-danger">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-circle-plus"></i> Total new: <strong>{{ isset($counters[0]) ? $counters[0]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title font-monospace"><i class="fa-solid fa-table-list"></i> In process: <strong>{{ isset($counters[1]) ? $counters[1]->totalCounter : 0}}</strong></h5>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="btnUserType" value="{{ Auth::user()->user_type }}">
    <div class="row mt-4">
        <div class="col-md-12">
            <div id="returns-table"></div>
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

<script src="/js/index.js"></script>


@endsection