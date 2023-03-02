@extends('layouts.master.main')

@section('content')
<div class="row">
    <div class="col">
        <div class="row">
            <h2><strong>Import Part Number</strong></h2>
        </div>   
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        {{-- alert when registered --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible mb-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                </button>
            </div>
        @endif
        {{-- end of alert --}}

        <div class="card" style="padding: 2rem;">
            <div class="row">
                <div class="col-md-8"></div>
                <div class="col-md-2 text-end pe-1">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal"><i class="bx bx-import me-sm-2"></i>
                        <span class="d-none d-sm-inline-block">Import</span></button>
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#addPart"><i class="bx bx-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Manual</span></button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="datatables-basics table border-top part-datatable">
                    <thead>
                        <tr>
                            <th>part Number</th>
                            <th>Part Name</th>
                            <th>Limit Quantity</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="addPart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-edit-user">
        <div class="modal-content p-2 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3>Part Information</h3>
                    <p>Mastering Detail Part Information</p>
                </div>
                <form method="POST" action="{{ route('part-number.master.insertData') }}" id="editUserForm" class="row g-3">
                    @method('POST')
                    @csrf
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="part_name">Part Name</label>
                        <input type="text" id="part_name" name="part_name" class="form-control" placeholder="Oil Pan" required/>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="part_number">Part Number</label>
                        <input type="text" id="part_number" name="part_number" class="form-control" placeholder="212130-21250" required/>
                    </div>
                    <div class="col-6">
                        <label class="form-label" for="qty_limit">Quantity Limit</label>
                        <input type="number" id="qty_limit" name="qty_limit" class="form-control" placeholder="1920" required/>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="reset" class="btn btn-label-secondary me-sm-3 me-1" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- end modal --}}

<!-- Modal -->
<div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Upload Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <input type="file" id="file" name="file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- end modal --}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
    
    $(document).ready(function () {
        $('.part-datatable').DataTable({
            ajax: `{{ route('part-number.master.getData') }}`,
            columns: [
                { data: 'part_number' },
                { data: 'part_name' },
                { data: 'qty_limit' },
            ],
        });
    });
</script>

@endsection