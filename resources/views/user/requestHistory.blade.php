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
<!-- Search -->
<div class="col-md-4">
    <form class="navbar-nav-left d-flex" action="{{route('user.requestHistory')}}" method="GET">
        <div class="input-group">
            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
            <input id="inputSearch" type="text" name="search" class="form-control" placeholder="Search..." value=""/>
        </div>
    </form>
</div>
<!-- /Search -->
<br>

<div class="table-responsive">
    <table style="width:100%" id='table' >
      <thead>
  <tr align="center">
    <th style="width:10%">ID</th>
    <th style="width:20%">PENGAMBIL</th>
    <th style="width:20%">TANGGAL</th>
    <th style="width:20%">JADWAL PENGAMBILAN</th>
    <th style="width:10%">STATUS</th>
    <th style="width:20%">AKSI</th>
  </tr>
      </thead>
      <tbody>
@foreach($reqList['data'] as $key =>$req)
  <tr>
    <td style="text-align: center">{{$req->id}}</td>
    <td>{{$req->nama}}</td>
    <td style="text-align: center">{{$req->tanggal}}</td>
    <td style="text-align: center">{{$req->jam_pengambilan}}</td>
    <td style="text-align: center"><span class="badge bg-label-warning me-1">{{$req->status}}</span></td>
    <td style="text-align: center">
        <button class="btn rounded-pill me-2 btn-label-info" onclick="window.location.href='{{route('user.historyDetail',['id'=>$req->id])}}'">Detail</button>
        <form style="display:inline-flex" action="{{route('user.requestCancel',['id'=>$req->id])}}" method="POST">
            <button type="submit" class="btn rounded-pill me-2 btn-label-danger">Batal</button>
            @csrf
        </form>
    </td>
  </tr>
      </tbody>
@endforeach
</table>
</div>
<br>
<div class="pagination">
    @foreach($reqList['links'] as $key =>$item)
    @if($item['url'] != null)
    @if(!$item['active'])
        <a href="{{$item['url']}}"><?php echo $item['label'];?></a>
    @else
        <a class="active" href="{{$item['url']}}"><?php echo $item['label'];?></a>
    @endif
    @endif
    @endforeach
</div>

<hr/>

@if ($message = Session::get('message'))
    <script>alert('{{ $message }}')</script>
@endif
<script>
    //get search value from url
    var url = new URL(window.location.href);
    var search = url.searchParams.get("search");
    //set search value to input
    document.getElementById("inputSearch").value = search;
</script>

@endsection

