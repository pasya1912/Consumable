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

<h2>Request List</h2>
<form action="{{route('user.requestHistory')}}" method="get">
    <input id="inputSearch"type="text" name="search" placeholder="Search" value="">
    <input type="submit" value="Search">
</form>
<table style="width:100%" id='table'>
  <tr>
    <th style="width:10%">id</th>
    <th style="width:20%">Pengambil</th>
    <th style="width:20%">Tanggal</th>
    <th style="width:20%">Jadwal Pengambilan</th>
    <th style="width:10%">Status</th>
    <th style="width:20%">Aksi</th>

  </tr>
@foreach($reqList['data'] as $key =>$req)
  <tr>
    <td>{{$req->id}}</td>
    <td>{{$req->nama}}</td>
    <td>{{$req->tanggal}}</td>
    <td>{{$req->jam_pengambilan}}</td>
    <td>{{$req->status}}</td>
    <td>
        <button onclick="window.location.href='{{route('user.historyDetail',['id'=>$req->id])}}'">Detail</button>
        <form style="display:inline-flex"action="{{route('user.requestCancel',['id'=>$req->id])}}" method="POST">
            @csrf
        <input type="submit" value="Batalkan">
        </form>
    </td>

  </tr>
@endforeach
</table>
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

