@extends('layouts.master.main')

@section('content')
<div class="row">
    <h2><strong>WIP Stock Dashboard</strong></h2>
</div>        
<div class="row">
    <div class="col-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <button type="button" class="btn btn-lg btn-label-warning px-5 py-4">TCC</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end mt-2">
                        <span class="mb-1">Total F/G</span>
                        <h3 class="card-title text-nowrap mt-2"><strong>12.000 PCS</strong></h3>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <small class="text-muted">Today, 16 Februari 2023 (10:11 am)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <button type="button" class="btn btn-lg btn-label-danger px-5 py-4">OPN</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end mt-2">
                        <span class="mb-1">Total F/G</span>
                        <h3 class="card-title text-nowrap mt-2"><strong>11.231 PCS</strong></h3>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <small class="text-muted">Today, 16 Februari 2023 (10:11 am)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <button type="button" class="btn btn-lg btn-label-info px-5 py-4">CSH</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-end mt-2">
                        <span class="mb-1">Total F/G</span>
                        <h3 class="card-title text-nowrap mt-2"><strong>10.561 PCS</strong></h3>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <small class="text-muted">Today, 16 Februari 2023 (10:11 am)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="true">TCC</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="false">OPN</button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-messages" aria-controls="navs-pills-top-messages" aria-selected="false">CSH</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
                    <div class="card-body">
                        <div id="incomeChart"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                    <div class="card-body">
                        <div id="totalRevenueChart"></div>     
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-pills-top-messages" role="tabpanel">
                    <div class="card-body">
                        <div id="profileReportChart"></div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection