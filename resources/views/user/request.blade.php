<!DOCTYPE html>
<html>
<style>
table, th, td {
  border:1px solid black;
}
</style>
<body>

<h2>Item to request</h2>
<?php
?>
<table style="width:100%" id='table'>
  <tr>
    <th>Code</th>
    <th>name</th>
    <th>Jumlah</th>
    <th>Aksi</th>
  </tr>
<?php
?>
@foreach($arr['items'] as $key =>$item)
  <tr>
    <td>{{$item->code_item}}</td>
    <td>{{$item->name_item}}</td>
    <td>
        <input id="inputRequest{{$key}}" type="number" onchange="ubahJumlah({{$key}})" value="{{$arr['cart'][$key]['jumlah']}}"/> Max: {{$item->remaining_quota       }}   {{$item->satuan_oca}}
    </td>
    <td>
        <button onclick="hapusItem({{$key}})">Hapus</button>
    </td>
  </tr>
@endforeach
</table>

<p>To understand the example better, we have added borders to the table.</p>

<hr/>

<form action="{{route('user.requestStore')}}" method="POST">
    @csrf
    <input type="text" name="nama_pj" placeholder="Pengambil barang"/>
    <br>
    <select name="jadwal">
        @foreach($arr['jadwal'] as $shift)
            <option value="{{$shift->id}}">Shift {{$shift->id}} ({{$shift->awal}} - {{$shift->akhir}})</option>
        @endforeach
    </select>
    <br><button type="submit">Request</button>
</form>

<script>
function  ubahJumlah(index)
{
    //call with vanila js ajax to route user.requestUbahJumlah
    var jumlah = document.querySelector("#inputRequest"+index).value;
var code = document.getElementsByTagName("td")[index*3].innerHTML;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("demo").innerHTML = this.responseText;
            console.log(this.responseText);
        }
    };
    xhttp.open("POST", "/request/ubah-item/"+index+"", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader('X-CSRF-TOKEN', "{{csrf_token()}}");
    xhttp.send("action=update&jumlah="+jumlah);
}

function hapusItem(index)
{
    //call with vanila js ajax to route user.requestHapusItem
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("demo").innerHTML = this.responseText;
            console.log(this.responseText);
            //remove current pressed button row
            document.getElementsByTagName("tr")[index+1].remove();

        }
    };
    xhttp.open("POST", "/request/ubah-item/"+index+"", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.setRequestHeader('X-CSRF-TOKEN', "{{csrf_token()}}");
    xhttp.send("action=delete");

}
</script>
@if ($message = Session::get('message'))
    <script>alert('{{ $message }}')</script>
@endif
</body>
</html>

