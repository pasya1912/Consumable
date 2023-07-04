@extends('layouts.master.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="row">
                <h2><strong>Request Detail</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text">Status:
                            @if ($reqDetail->status == 'approved')
                                <span class="badge rounded-pill bg-label-success">{{ $reqDetail->status }}</span>
                            @elseif($reqDetail->status == 'rejected')
                                <span class="badge rounded-pill bg-label-danger">{{ $reqDetail->status }}</span>
                            @elseif($reqDetail->status == 'revised')
                                <span class="badge rounded-pill bg-label-light text-primary">{{ $reqDetail->status }}</span>
                            @elseif($reqDetail->status == 'canceled')
                                <span class="badge rounded-pill bg-label-danger">{{ $reqDetail->status }}</span>
                            @else
                                <span class="badge rounded-pill bg-light text-dark">{{ $reqDetail->status }}</span>
                            @endif
                        </h3>
                    </div>
                    <div class="col-md-6 export mr-2">
                        <div class="w-100  d-flex align-items-end gap-2 justify-content-end">

                            <a href="{{ route('admin.requestPrintGenerate', ['id' => $reqDetail->id]) }}"
                                class="btn btn-warning ">Generate</a>
                        <a href="{{ route('admin.requestPrint', ['id' => $reqDetail->id]) }}"
                            class="btn btn-warning ">Print</a>
                        </div>
                    </div>
                </div>
                <div class="row table-responsive">
                    <table class="table">
                        <tr>
                            <th style="text-align: center">Code</th>
                            <th style="text-align: center">name</th>
                            <th style="text-align: center">Jumlah</th>
                            <th style="text-align: center">Note</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($reqDetail->items as $key => $item)
                                <tr>
                                    <td style="text-align: center">{{ $item->code_item }}</td>
                                    <td style="text-align: center">{{ $item->name_item }}</td>
                                    <td style="text-align: center">
                                        {{ $item->jumlah }}
                                    </td>
                                    <td style="text-align: center">
                                        <span class="">{{ $item->admin_note }}</span>
                                    </td>

                                </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
                <br>
                <div clas="w-50">
                    <div class="mb-3  w-50">
                        <label for="nama_pj" class="form-label">Nama Pengambil</label>
                        <input type="text" class="form-control" id="nama_pj" placeholder="{{ $reqDetail->nama }}"
                            disabled />
                    </div>
                    <div class="mb-3 w-50">
                        <label for="jadwal_pengambilan" class="form-label">Jadwal Pengambilan</label>
                        <input type="text" class="form-control" id="jadwal_pengambilan"
                            placeholder="{{ $reqDetail->jam_pengambilan }}" disabled />
                    </div>
                    <div class="mb-3 w-50">
                        <label for="tanggal" class="form-label">Tanggal Pengambilan</label>
                        <input type="text" class="form-control" id="tanggal" placeholder="{{ $reqDetail->tanggal }}"
                            disabled />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($message = Session::get('message'))
    @endif
@endsection
