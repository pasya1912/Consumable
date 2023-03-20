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
<form action="{{url('admin/budget')}}" method="get">
    <input id="inputSearch" type="text" name="search" placeholder="Search" value="">
    <input type="submit" value="Search">
</form>

<p>Sort By:</p>
<a  href="{{route('admin.budget')}}?sort_by=code_item">Code Barang</a>
<a  href="{{route('admin.budget')}}?sort_by=name_item">Nama Barang</a>
<a  href="{{route('admin.budget')}}?sort_by=category">Kategori Barang</a>
<form action="{{route('admin.importBudget')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" id="file">
    <input type="submit" value="Import">
</form>
<a href="{{asset('template/template_budget.xlsx')}}">Download Template</a>
<table style="width:100%" id='table'>
  <tr>
    <th style="width:10%">Code Barang</th>
    <th style="width:20%">Nama Barang</th>
    <th>Kategori Barang</th>
    <th style="width:20%">User</th>
    <th style="width:20%">Batas Budget</th>
    <th style="width:10%">Budget Tersisa</th>


  </tr>

@foreach($budgets['data'] as $key =>$budget)
  <tr>
    <td>{{$budget['code_item']}}</td>
    <td>{{$budget['name_item']}}</td>
    <td>{{$budget['category']}}</td>
    <td>{{$budget['user']}}</td>
    <td>{{$budget['quota']}}</td>
    <td>{{$budget['remaining_quota']}}</td>



  </tr>
@endforeach
</table>
<div class="pagination">
    @foreach($budgets['links'] as $key =>$item)
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

