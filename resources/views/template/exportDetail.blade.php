<table>
    <thead>
    <tr>
        <th>Department:</th>
        <td>{{$exportDetail['user']}}</td>

    </tr>
    <tr>
        <th >Pengambil:</th>
        <td>{{$exportDetail['nama']}}</td>
    </tr>
    <tr>
        <th>Status:</th>
        <td>{{$exportDetail['status']}}</td>
    </tr>
    <tr>
        <th >Tanggal:</th>
        <td>{{$exportDetail['tanggal']}}</td>
    </tr>
    <tr>

    </tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama Item / Barang</th>
        <th rowspan="2">Qty</th>
        <th rowspan="2">Satuan</th>
        <th rowspan="2">Sisa Budget</th>
        <th colspan="3">Location</th>
    </tr>
    <tr>
        <th style="width:50%">Area</th>
        <th>Dock</th>
        <th>No</th>
    </tr>
    </thead>
    <tbody>
    @foreach($exportDetail['data'] as $key =>$item)
    <tr>
    	<td>{{$key+1}}</td>
		<td>{{$item->name_item}}</td>
        <td>{{$item->jumlah}}</td>
        <td>{{$item->satuan_oca}}</td>
        <td>{{$item->remaining_quota}}</td>
        <td>{{$item->area}}</td>
        <td>{{$item->lemari}}</td>
        <td>{{$item->no2}}</td>

    </tr>
    @endforeach
    </tbody>
</table>
