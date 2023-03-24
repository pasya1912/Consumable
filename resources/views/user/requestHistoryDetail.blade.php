@extends('layouts.master.main')

@section('content')
<style>
    th, td {
     border-bottom: 1px solid #ddd;
   }
     </style>
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
                    <div class="col-md-10"></div>
                </div>
<p>
    <span class="badge rounded-pill bg-label-warning">Status:&nbsp{{$reqDetail->status}}</span>
</p>
<div class="table-responsive">
    <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
      <thead>
  <tr align="center">
    <th>CODE</th>
    <th>NAME</th>
    <th>JUMLAH</th>
  </tr>
      </thead>
      <tbody>
@foreach($reqDetail->items as $key =>$item)
  <tr>
    <td style="text-align: center">{{$item->code_item}}</td>
    <td style="text-align: center">{{$item->name_item}}</td>
    <td style="text-align: center">
        {{$item->jumlah}}
    </td>
  </tr>
      </tbody>
@endforeach
</table>
<br>
    <div class="mb-3">
    <label for="nama_pj" class="form-label">Nama Pengambil</label>
    <input type="text" class="form-control" id="nama_pj" placeholder="{{$reqDetail->nama}}" disabled/>
    </div>
    <div class="mb-3">
        <label for="jadwal_pengambilan" class="form-label">Jadwal Pengambilan</label>
        <input type="text" class="form-control" id="jadwal_pengambilan" placeholder="{{$reqDetail->jam_pengambilan}}" disabled/>
    </div>
    <div class="mb-3">
        <label for="tanggal" class="form-label">Tanggal Pengambilan</label>
        <input type="text" class="form-control" id="tanggal" placeholder="{{$reqDetail->tanggal}}" disabled/>
    </div>

    @if ($message = Session::get('message'))
    <script>alert('{{ $message }}')</script>
@endif
@endsection

