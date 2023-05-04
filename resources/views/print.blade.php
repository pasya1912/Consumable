<!DOCTYPE html>
<title>Form Order #{{$reqDetail->id}}</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<html>

<head>
    <style>
        .container {
            position: relative;
            width: 100%;
            height: auto;
            margin: 0;
            padding: 0;
            left: 0;
            top: 0;
            overflow: auto;
        }

        .table-wrapper {
            height: 12rem;
            width: 100%;
            position: relative;
            margin-top: 0.5rem;
            overflow: auto;
            /* clear floats */
        }

        table {
            border-collapse: collapse;
            width: 100%;

        }

        th,
        td {
            padding: 0.5rem;
            text-align: start;
            border: 1px solid #ccc;
        }

        th {
            background-color: #ccc;
            font-weight: bold;
        }

        .table1 {
            float: left;
            width: 40%;
        }

        .table2 {
            float: right;
            width: 40%;
        }

        .wrapper-2 {
            position: relative;
            margin-top: 1rem;
            width: 100%;
            height: 23rem;

            overflow: auto;
        }

        .wrapper-last {

            position: absolute;
            margin-top: 1rem;
            width: 100%;
            overflow: auto;


            height: 20rem;
            margin-bottom: 1rem;
        }

        .announce {
            float: left;
            width: 38%;
            border: 1px solid black;

        }
        .announce ul {
            margin-right: 0.5rem;
        }

        .tandatangan {
            float: right;
            width: 60%;
            font-size: 0.7rem;

        }
        .tandatangan td{
            text-align: center;
        }
         .wrapper-last  p{

            text-align: center;
            font-weight: bold;
            color: red;
            text-decoration: underline;
        }
        .container h1 {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }


    </style>
</head>

<body>
    <div class="container">
        <h1>Form Order Consumable Unit</h1>
        <div class="table-wrapper">

            <table class="table1">

                <tbody>
                    <tr >
                        <th>User/Department</th>
                        <th>:</th>
                        <td   style="padding-right: 10rem;">{{$reqDetail->user}}</td>
                    </tr>
                    <tr>
                        <th>Penanggung Jawab</th>
                        <th>:</th>
                        <td style="padding-right: 10rem;"></td>
                    </tr>
                </tbody>
            </table>
            <table class="table2">
                <tbody>
                    <tr>
                        <th>Tanggal</th>
                        <th>:</th>
                        <td>{{$reqDetail->tanggal}}</td>
                    </tr>
                    <tr>
                        <th>Shift</th>
                        <th>:</th>
                        <td>{{$reqDetail->shift}}</td>
                    </tr>
                    <tr>
                        <th>Jam Pengambilan</th>
                        <th>:</th>
                        <td>{{$reqDetail->jam_pengambilan}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="wrapper-2">
            <table class="table-info">
                <thead>
                    <tr>
                        <th style="width:5%"rowspan="2">No</th>
                        <th style="width:50%"rowspan="2">Nama Item / Barang</th>
                        <th rowspan="2">Qty</th>
                        <th rowspan="2">Satuan</th>
                        <th rowspan="2">Sisa Budget</th>
                        <th colspan="3">Location</th>
                    </tr>
                    <tr>
                        <th style="width:50%">Area</th>
                        <th>Dock</th>
                        <th>No</th>
                </thead>
                <tbody>
                    @foreach($reqDetail->data as $key =>$item)
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
        </div>
        <div class="wrapper-last">
            <div class="announce">

                <p>PERHATIAN!!!</p>
                <ul>
                    <li>User wajib membawa Form ini ketika mengambil Barang</li>
                    <li>Menggunakan APD sesuai standart Area PPIC Unit</li>
                    <li>Pastikan Form sudah ttd atasan masing - masing</li>
                    <li>Mohon mengambil Barang sesuai dengan Jadwal pengambilannya</li>
                    <li>Jika Urgent, segera lakukan SCW ke atasan masing - masing</li>
                    <li>Apabila Sisa Budget sudah 0 atau Minus, silahkan dibuatkan Form Over Budget</li>

                </ul>
            </div>
            <div class="tandatangan">
                <table>
                    <thead>
                        <tr>
                            <th colspan="3">User/Department</td>
                            <th>Penanggung Jawab</td>
                        </tr>
                        <tr>
                            <td>Diminta oleh</td>
                            <td>Mengetahui</td>
                            <td>Diterima oleh</td>
                            <td>Diserahkan oleh</td>
                        </tr>

                    </thead>
                    <tbody>
                    <tr >
                        <td  style="height: 6rem;"></td>
                        <td  style="height: 6rem;"></td>
                        <td  style="height: 6rem;"></td>
                        <td  style="height: 6rem;"></td>
                    </tr>
                    <tr >
                        <td  style="height: 0.5rem;"></td>
                        <td  style="height: 0.5rem;"></td>
                        <td  style="height: 0.5rem;"></td>
                        <td  style="height: 0.5rem;"></td>
                    </tr>
                    </tbody>
            </div>
        </div>
</body>

</html>
