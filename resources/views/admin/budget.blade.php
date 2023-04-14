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
                <h2><strong>Budget</strong></h2>
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
                            <a href="{{ asset('template/template_budget.xlsx') }}" class="btn btn-success btn-lg">Download
                                Template</a>
                        </div>

                        <div class="col text-end">
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#basicModal"><i
                                    class="bx bx-import me-sm-2"></i> <span
                                    class="d-none d-sm-inline-block">Import</span></button>
                        </div>
                    </div>
                </div>
                <br>

                <!-- Search -->
                <div class="col-md-4">
                    <form class="navbar-nav-left d-flex" action="{{ url('admin/budget') }}" method="GET">
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
                        <li><a class="dropdown-item" href="{{ route('admin.budget') }}?sort_by=code_item">Code Barang</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.budget') }}?sort_by=name_item">Nama Barang</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.budget') }}?sort_by=category">Kategori Barang</a>
                        </li>
                    </ul>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('admin.importBudget') }}" method="POST" enctype="multipart/form-data">
                                @method('POST')
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel1">Upload Budget</h5>
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
                <br>

                <table style="width:100%" id='table' cellpadding=10 cellspasing=15>
                    <tr align="center">
                        <th style="width:10%">Code Barang</th>
                        <th style="width:20%">Nama Barang</th>
                        <th>Kategori Barang</th>
                        <th style="width:20%">User</th>
                        <th style="width:20%">Batas Budget</th>
                        <th style="width:10%">Budget Tersisa</th>


                    </tr>

                    @foreach ($budgets['data'] as $key => $budget)
                        <tr>
                            <td style="text-align: center">{{ $budget['code_item'] }}</td>
                            <td style="text-align: center">{{ $budget['name_item'] }}</td>
                            <td style="text-align: center">{{ $budget['category'] }}</td>
                            <td style="text-align: center">{{ $budget['user'] }}</td>
                            <td style="text-align: center "><span class="text text-success">{{ $budget['quota'] }}</span>
                            </td>
                            <td style="text-align: center">
                                @if ($budget['remaining_quota'] < $budget['quota'] * 0.25)
                                    <span class="text text-danger">{{ $budget['remaining_quota'] }}</span>
                                @elseif($budget['remaining_quota'] < $budget['quota'] * 0.5)
                                    <span class="text text-warning">{{ $budget['remaining_quota'] }}</span>
                                @else
                                    <span class="text text-success">{{ $budget['remaining_quota'] }}</span>
                                @endif

                            </td>



                        </tr>
                    @endforeach
                </table>
                <br>
                <div class="pagination">
                    @foreach ($budgets['links'] as $key => $item)
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
                <script>
                    //get search value from url
                    var url = new URL(window.location.href);
                    var search = url.searchParams.get("search");
                    //set search value to input
                    document.getElementById("inputSearch").value = search;
                </script>
            @endsection
