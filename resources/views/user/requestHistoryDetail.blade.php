<!DOCTYPE html>
<html>
<style>
table, th, td {
  border:1px solid black;
}
</style>
<body>

<h2 style="text-align: center;">Request Detail</h2>
<h3 >Status: {{$reqDetail->status}}</h3>
<table style="width:100%" id='table'>
  <tr>
    <th>Code</th>
    <th>name</th>
    <th>Jumlah</th>
  </tr>
@foreach($reqDetail->items as $key =>$item)
  <tr>
    <td>{{$item->code_item}}</td>
    <td>{{$item->name_item}}</td>
    <td>
        {{$item->jumlah}}
    </td>

  </tr>
@endforeach
</table>

<p>To understand the example better, we have added borders to the table.</p>

<hr/>


    Nama Pengambil :<input type="text" name="nama_pj" placeholder="{{$reqDetail->nama}}" disabled/>
    <br>
    Jam Pengambilan :<input type="text" name="jadwal_pengambilan" placeholder="{{$reqDetail->jam_pengambilan}}" disabled/>
    <br>
    Tanggal Pengambilan :<input type="text" name="tanggal" placeholder="{{$reqDetail->tanggal}}" disabled/>
    <br>




    @if ($message = Session::get('message'))
    <script>alert('{{ $message }}')</script>
@endif
</body>
</html>

