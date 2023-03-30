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
            <h2><strong>User List</strong></h2>
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
    <form class="navbar-nav-left d-flex" action="{{route('admin.userList')}}" method="GET">
        <div class="input-group">
            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
            <input id="inputSearch" type="text" name="search" class="form-control" placeholder="Search..." value=""/>
        </div>
    </form>
</div>
<!-- /Search -->
<br>
<!-- Dropdown -->
<div class="btn-group me-3">
    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Sort By
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="{{route('admin.userList')}}?sort_by=id">ID</a></li>
      <li><a class="dropdown-item" href="{{route('admin.userList')}}?sort_by=name">Departement</a></li>
      <li><a class="dropdown-item" href="{{route('admin.userList')}}?sort_by=username">Username</a></li>
      <li><a class="dropdown-item" href="{{route('admin.userList')}}?sort_by=email">Email</a></li>
    </ul>
  </div>
<br>
<table style="width:100%" id='table' cellpadding=10 cellspasing=15>
  <tr align="center">
    <th style="width:10%">ID</th>
    <th style="width:20%">Departement</th>
    <th style="width:20%">Username/User</th>
    <th style="width:20%">Aksi</th>
  </tr>

@foreach($userList['data'] as $key =>$item)
  <tr>
    <td style="text-align: center">{{$item['id']}}</td>
    <td style="text-align: center">{{$item['name']}}</td>
    <td style="text-align: center">{{$item['username']}}</td>
    <td style="text-align: center">
        <form style="display:inline-flex" action="{{route('admin.userDelete',['username'=>$item['username']])}}" method="POST">
            <button type="submit" class="btn rounded-pill me-2 btn-label-danger">Hapus</button>
            @csrf
        </form>
    </td>
  </tr>
@endforeach
</table>
<br>
<div class="pagination">
    @foreach($userList['links'] as $key =>$item)
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

