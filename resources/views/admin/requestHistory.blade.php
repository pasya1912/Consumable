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
                <h2><strong>Request List</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-10"></div>
                </div>

                <!-- Search -->
                <div class="col-md-4">
                    <form class="navbar-nav-left d-flex" action="{{ route('admin.requestHistory') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                            <input id="inputSearch" type="text" name="search" class="form-control"
                                placeholder="Search..." value="" />
                        </div>
                    </form>
                </div>
                <!-- /Search -->
                <br>
                <!-- Dropdown -->
                <div class="btn-group me-3">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item"
                                href="{{ route('admin.requestHistory') }}?sort_by=jam_pengambilan">Jadwal Pengambilan</a>
                        </li>
                    </ul>
                </div>
                <br>
                <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
                    <t>
                        <th style="width:10%">id</th>
                        <th>User/Departement</th>
                        <th style="width:20%">Pengambil</th>
                        <th style="width:20%">Tanggal</th>
                        <th style="width:20%">Jadwal Pengambilan</th>
                        <th style="width:10%">Status</th>
                        <th style="width:20%">Aksi</th>

                        </tr>
                        @foreach ($reqList['data'] as $key => $req)
                            <tr>
                                <td style="text-align: center">{{ $req->id }}</td>
                                <td style="text-align: center">{{ $req->user }}</td>
                                <td style="text-align: center">{{ $req->nama }}</td>
                                <td style="text-align: center">{{ $req->tanggal }}</td>
                                <td style="text-align: center">{{ $req->jam_pengambilan }}</td>
                                <td style="text-align: center">
                                    @if ($req->status == 'approved')
                                        <span class="badge bg-success text-dark w-100 me-1">{{ $req->status }}</span>
                                    @elseif($req->status == 'rejected')
                                        <span class="badge bg-danger text-dark w-100 me-1">{{ $req->status }}</span>
                                    @elseif($req->status == 'revised')
                                        <span class="badge bg-dark text-light w-100 me-1">{{ $req->status }}</span>
                                    @elseif($req->status == 'canceled')
                                        <span class="badge bg-warning text-dark w-100 me-1">{{ $req->status }}</span>
                                    @elseif($req->status == 'wait')
                                        <span class="badge bg-light text-dark w-100 me-1">{{ $req->status }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <button class="btn rounded-pill me-2 btn-label-info"
                                        onclick="window.location.href='{{ route('admin.requestDetail', ['id' => $req->id]) }}'">Detail</button>
                                </td>

                            </tr>
                        @endforeach
                </table>
                <br>
                <div class="pagination">
                    @foreach ($reqList['links'] as $key => $item)
                        @if ($item['url'] != null)
                            @if (!$item['active'])
                                <a href="{{ $item['url'] }}"><?php echo $item['label']; ?></a>
                            @else
                                <a class="active" href="{{ $item['url'] }}"><?php echo $item['label']; ?></a>
                            @endif
                        @endif
                    @endforeach
                </div>
                <hr />

                @if ($message = Session::get('message'))
                    <script>
                        alert('{{ $message }}')
                    </script>
                @endif
                <script>
                    //get search value from url
                    var url = new URL(window.location.href);
                    var search = url.searchParams.get("search");
                    //set search value to input
                    document.getElementById("inputSearch").value = search;
                </script>
            @endsection
