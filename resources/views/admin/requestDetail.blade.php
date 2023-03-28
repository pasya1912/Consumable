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
            <h2><strong>Request List</strong></h2>
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
    <form action="{{route('admin.requestUpdate',['id'=>$reqDetail->id])}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="defaultSelect" class="form-label">Update Status</label>
        <select id="defaultSelect" class="form-select" name="status">
        <option>Pilih salah satu</option>
        <option value="rejected">Rejected</option>
        <option value="approved">Approved</option>
        <option value="canceled">Canceled</option>
        </select>
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
    </form>
<br>
<table style="width:100%" id='table' cellpadding=10 cellspasing=15>
  <tr align="center">
    <th>Code</th>
    <th>Name</th>
    <th>Jumlah</th>
  </tr>
@foreach($reqDetail->items as $key =>$item)
  <tr>
    <td style="text-align: center">{{$item->code_item}}</td>
    <td style="text-align: center">{{$item->name_item}}</td>
    <td style="text-align: center">
        {{$item->jumlah}}
    </td>

  </tr>
@endforeach
</table>

<br>
<div class="mb-3">
    <label for="defaultFormControlInput" class="form-label">Nama Pengambil</label>
    <input type="text" class="form-control" id="defaultFormControlInput" placeholder="{{$reqDetail->nama}}" aria-describedby="defaultFormControlHelp" disabled />
  </div>
  <div class="mb-3">
    <label for="defaultFormControlInput" class="form-label">Jam Pengambilan</label>
    <input type="text" class="form-control" id="defaultFormControlInput" placeholder="{{$reqDetail->jam_pengambilan}}" aria-describedby="defaultFormControlHelp" disabled />
  </div>
  <div class="mb-3">
    <label for="defaultFormControlInput" class="form-label">Tanggal Pengambilan</label>
    <input type="text" class="form-control" id="defaultFormControlInput" placeholder="{{$reqDetail->tanggal}}" aria-describedby="defaultFormControlHelp" disabled />
  </div>

    @if ($message = Session::get('message'))
    <script>alert('{{ $message }}')</script>
@endif
@endsection

