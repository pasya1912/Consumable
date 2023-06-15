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
                <h2><strong>User List</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-10"></div>
                    <div class="row">
                        <div class="col">
                            <a href="{{ asset('template/template_user.xlsx') }}" class="btn btn-success btn-lg">Download
                                Template</a>
                        </div>

                        <div class="col text-end">
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#basicModal"><i
                                    class="bx bx-import me-sm-2"></i> <span
                                    class="d-none d-sm-inline-block">Import</span></button>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="col-md-4 mt-3">
                    <form class="navbar-nav-left d-flex" action="{{ route('admin.userList') }}" method="GET">
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
                        <li><a class="dropdown-item" href="{{ route('admin.userList') }}?sort_by=id">ID</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.userList') }}?sort_by=nama">Departement</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.userList') }}?sort_by=username">Username</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.userList') }}?sort_by=email">Email</a></li>
                    </ul>
                </div>
                <br>
                <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.importUser') }}" method="POST" enctype="multipart/form-data">
                                @method('POST')
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Upload User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-3">
                                            <input type="file" id="file" name="file" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <hr />
                <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
                    <tr style="text-align:center">
                        <th style="width:10%">ID</th>
                        <th style="width:20%">Departement</th>
                        <th style="width:20%">Username/User</th>
                        <th style="width:20%">Aksi</th>
                    </tr>

                    @foreach ($userList['data'] as $key => $item)
                        <tr>
                            <td style="text-align: center">{{ $item['id'] }}</td>
                            <td style="text-align: center">{{ $item['nama'] }}</td>
                            <td style="text-align: center">{{ $item['username'] }}</td>
                            <td style="text-align: center">
                                <form style="display:inline-flex"
                                    action="{{ route('admin.userDelete', ['username' => $item['username']]) }}"
                                    method="POST">
                                    <button type="submit" class="btn rounded-pill me-2 btn-label-danger">Hapus</button>
                                    @csrf
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <div class="pagination">
                    @foreach ($userList['links'] as $key => $item)
                        @if ($item['url'] != null)
                            @if (!$item['active'])
                                <a href="{{ $item['url'] }}"><?php echo $item['label']; ?></a>
                            @else
                                <a class="active" href="{{ $item['url'] }}"><?php echo $item['label']; ?></a>
                            @endif
                        @endif
                    @endforeach
                </div>
            @endsection
            @section('script')
                <script>
                    //get search value from url
                    var url = new URL(window.location.href);
                    var search = url.searchParams.get("search");
                    //set search value to input
                    document.getElementById("inputSearch").value = search;
                </script>
            @endsection
