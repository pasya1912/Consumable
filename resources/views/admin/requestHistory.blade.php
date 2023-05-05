@extends('layouts.master.main')
@section('style')
    <style>
        th,
        td {
            border-bottom: 1px solid #ddd;
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-primary-light {
            background-color: rgba(0, 123, 255, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-secondary-light {
            background-color: rgba(108, 117, 125, 0.5) !important;
            /* Success color with opacity 0.5 */
        }

        .bg-dark-light {
            background-color: rgba(52, 58, 64, 0.5) !important;
            /* Success color with opacity 0.5 */
        }
    </style>
@endsection
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
                <!-- Search -->
                <div class="row">
                    <div class="col-md-4">
                        <form class="navbar-nav-left d-flex" action="{{ route('admin.requestHistory') }}" method="GET">
                            <div class="input-group">
                                <span class="input-group-text"><i class="tf-icons bx bx-search"></i></span>
                                <input id="inputSearch" type="text" name="search" class="form-control"
                                    placeholder="Search..." value="" />
                            </div>
                        </form>
                    </div>
                    <div class="col text-end">
                        <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportListModal"><i
                                class="bx bx-export me-sm-2"></i> <span class="d-none d-sm-inline-block">Export</span></a>
                    </div>
                </div>
                <!-- /Search -->
                <br>
                <!-- Dropdown -->
                <div class="btn-group ">
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
                <!-- Modal -->
                <div class="modal fade" id="exportListModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.requestListExport') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Export List</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <div class="form-group">
                                                <label for="from-date">From</label>
                                                <input type="date" class="form-control datetimepicker-input"
                                                    id="from-date" name="from" />
                                            </div>
                                            <div class="form-group">
                                                <label for="to-date">To</label>
                                                <input type="date" class="form-control" name="to" id="to-date" />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Export</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
                    <tr>
                        <th style="width:10%" class="w-10 text text-center">id</th>
                        <th class="text text-center">User/Departement</th>
                        <th style="width:20%" class="w-20 text text-center">Pengambil</th>
                        <th style="width:20%" class="w-20 text text-center">Tanggal</th>
                        <th style="width:20%" class="w-20 text text-center">Jadwal Pengambilan</th>
                        <th style="width:10%" class="w-10 text text-center">Status</th>
                        <th style="width:20%" class="w-20 text text-center">Aksi</th>

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
                                    <span class="badge bg-success-light text-dark w-100 me-1">{{ $req->status }}</span>
                                @elseif($req->status == 'rejected')
                                    <span class="badge bg-danger-light text-dark w-100 me-1">{{ $req->status }}</span>
                                @elseif($req->status == 'revised')
                                    <span class="badge bg-dark-light text-dark w-100 me-1">{{ $req->status }}</span>
                                @elseif($req->status == 'canceled')
                                    <span class="badge bg-warning-light text-dark w-100 me-1">{{ $req->status }}</span>
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
            @endsection
            @section('script')
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
                    // Initialize the "from" date picker

                    var from_date = document.getElementById("from-date");
                    var to_date = document.getElementById("to-date");

                    //set maxon from close
                    from_date.addEventListener("change", function() {
                        to_date.setAttribute("min", this.value);
                    });

                    //set min on to close
                    to_date.addEventListener("change", function() {
                        from_date.setAttribute("max", this.value);
                    });
                </script>
                <script>
                    $(document).ready(function() {
                        $('#table').DataTable({
                            "paging": false,
                            "info": false,
                            "searching": false,
                            "lengthChange": false,
                            "ordering": false,
                            "bInfo": false,
                            "bPaginate": false,
                            "bLengthChange": false,
                            "bFilter": false,
                            "bAutoWidth": false,
                            "responsive": true,
                            "columnDefs": [{
                                "targets": [0, 1, 2, 3, 4, 5, 6],
                                "className": "text text-center"
                            }]
                        });

                    });
                </script>
            @endsection
