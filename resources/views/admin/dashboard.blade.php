@extends('layouts.master.main')

@section('content')
    <style>
        th,
        td {
            border-bottom: 1px solid #ddd;
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="row">
                <h2><strong>Item Master</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                <canvas id="dashboardChart" width="400" height="100"></canvas>
                </div>
                <div class="row mt-5">
                    <h3><strong>Waiting List Request</strong></h3>
                    <div class="table-responsive">
                        <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
                            <thead>
                                <tr style="text-align:center">
                                    <th style="width:5%">ID Request</th>
                                    <th style="width:15%">Department</th>
                                    <th style="width:20%">Pengambil </th>
                                    <th style="width:30%">Tanggal Pengambilan</th>
                                    <th style="width:20%">Barang</th>
                                    <th style="width:10%">Detail</th>
                                </tr>
                            <tbody>
                                @if(count($requests))
                                @foreach($requests as $req)
                                    <tr>
                                        <td style="text-align: center">{{$req->id}}</td>
                                        <td style="text-align: center">{{$req->user}}</td>
                                        <td style="text-align: center">{{$req->nama}}</td>
                                        <td style="text-align: center">{{date('d-m-Y',strtotime($req->tanggal))}} {{$req->jam->awal}} - {{$req->jam->akhir}}</td>
                                        <td style="text-align: center">{{$req->request_item()->count()}} Item Berbeda</td>
                                        <td style="text-align: center"><a href="{{route('admin.requestDetail', ['id' => $req->id])}}" class="btn btn-primary">Detail</a></td>

                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" style="text-align: center">No Data</td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        //get search value from url

        var ctx = document.getElementById("dashboardChart").getContext("2d");

        var data = {
            labels: ["Senin", "Selasa", "Rabu","Kamis", "Jumat", "Sabtu", "Minggu"],
            datasets: [
                {
                    label: "All",
                    backgroundColor: "skyblue",
                    data: {{json_encode($chartData['all'])}}
                },{
                    label: "Waiting",
                    backgroundColor: "gray",
                    data: {{json_encode($chartData['wait'])}}
                },
                {
                    label: "Approved/Revised",
                    backgroundColor: "green",
                    data: {{json_encode($chartData['approved'])}}
                },
                {
                    label: "Rejected",
                    backgroundColor: "red",
                    data: {{json_encode($chartData['rejected'])}}
                }

            ]
        };

        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                barValueSpacing: 20,
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                        }
                    }]
                }
            }
        });

    </script>
@endsection
@section('top-script')
    <script>
        function doImage(code_item, image) {
            console.log(code_item);
            document.getElementById("code_item").value = code_item;
            if (image != null || image != '') {
                document.getElementById("image_preview").src = "{{ route('getImage', '') }}/" + image;
            }
        }

    </script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


@endsection
