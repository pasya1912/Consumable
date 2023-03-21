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

<h2>Item Master</h2>

<form action="{{route('admin.dashboard')}}" method="get">
    <input id="inputSearch" type="text" name="search" placeholder="Search" value="">
    <input type="submit" value="Search">
</form>

<form action="{{route('admin.importItems')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" id="file">
    <input type="submit" value="Import">
</form>
<a href="{{asset('template/template_item.xlsx')}}">Download Template</a>

<table style="width:100%" id='table'>
  <tr>
    <th style="width:10%">Code Barang</th>
    <th style="width:20%">Nama Barang</th>
    <th style="width:20%">Area</th>
    <th style="width:20%">Lemari</th>
    <th style="width:10%">Rak</th>
    <th style="width:20%">Satuan</th>

  </tr>

@foreach($items['data'] as $key =>$item)
  <tr>
    <td>{{$item['code_item']}}</td>
    <td>{{$item['name_item']}}</td>
    <td>{{$item['area']}}</td>
    <td>{{$item['lemari']}}</td>
    <td>{{$item['no2']}}</td>
    <td>{{$item['satuan']}}</td>


  </tr>
@endforeach
</table>

<div class="pagination">
    @foreach($items['links'] as $key =>$item)
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

