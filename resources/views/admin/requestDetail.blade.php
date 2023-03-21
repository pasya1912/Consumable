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
//form ubah status rejected approved canceled
<form action="{{route('admin.requestUpdateStatus',['id'=>$reqDetail->id])}}" method="POST">
    @csrf
    <select name="status">
        <option value="rejected">Rejected</option>
        <option value="approved">Approved</option>
        <option value="canceled">Canceled</option>
    </select>
    <input type="submit" value="Update">
</form>
<table style="width:100%" id='table'>
  <tr>
    <th>Code</th>
    <th>name</th>
    <th>Jumlah</th>
    <th>Note</th>
  </tr>
@foreach($reqDetail->items as $key =>$item)
  <tr>
    <td>{{$item->code_item}}</td>
    <td>{{$item->name_item}}</td>
    <td>
        {{$item->jumlah}}
    </td>
    <td>
        <textarea type="text" id="note{{$item->id}}" onchange="updateNote({{$item->id}})">{{$item->admin_note}}</textarea>
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
<script>
function updateNote(requestItemId)
{
    var note = document.getElementById('note'+requestItemId).value;
    var url = "{{route('admin.requestUpdateNote',['id'=>':id'])}}";
    url = url.replace(':id',requestItemId);
    console.log(url);

    //do postrequest to update note vanila
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
        }
    };
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("_token={{ csrf_token() }}"+"&note="+note);

}
</script>
</body>

</html>

