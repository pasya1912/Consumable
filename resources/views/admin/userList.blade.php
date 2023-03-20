<!DOCTYPE html>
<html>
<style>
table, th, td {
  border:1px solid black;
}

.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {background-color: #ddd;}

</style>

<body>

<h2>User List</h2>
<form action="{{route('admin.userList')}}" method="get">
    <input id="inputSearch" type="text" name="search" placeholder="Search" value="">
    <input type="submit" value="Search">
</form>

<p>Sort By:</p>
<a  href="{{route('admin.userList')}}?sort_by=id">Id</a>
<a  href="{{route('admin.userList')}}?sort_by=name">Departement</a>
<a  href="{{route('admin.userList')}}?sort_by=username">Username</a>
<a  href="{{route('admin.userList')}}?sort_by=email">Email</a>



<table style="width:100%" id='table'>
  <tr>
    <th style="width:10%">Id</th>
    <th style="width:20%">Departement</th>
    <th style="width:20%">Username/User</th>
    <th style="width:30%">Email</th>
    <th style="width:20%">Aksi</th>

  </tr>

@foreach($userList['data'] as $key =>$item)
  <tr>
    <td>{{$item['id']}}</td>
    <td>{{$item['name']}}</td>
    <td>{{$item['username']}}</td>
    <td>{{$item['email']}}</td>
    <td>
        <form action="{{route('admin.userDelete',['username'=>$item['username']])}}" method="POST">
            @csrf
            <input type="submit" value="Hapus">
        </form>
    </td>

  </tr>
@endforeach
</table>

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

<p>To understand the example better, we have added borders to the table.</p>

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
</body>
</html>

