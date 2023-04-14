@extends('layouts.master.main')
<?php
function checkPersen($quota, $remaining)
{
    if (($remaining / $quota) * 100.0 > 50.0) {
        return 'text text-success';
    } elseif (($remaining / $quota) * 100.0 > 10.0) {
        return 'text text-warning';
    } else {
        return 'text text-danger';
    }
}

?>

@section('content')
    <div class="row">
        <div class="col">
            <div class="row">
                <h2><strong>List Stock PPIC</strong></h2>
            </div>
        </div>
    </div>
    <div class="demo-inline-spacing mb-5 text-end">
        <a href="{{ route('user.request') }}" class="btn btn-danger">
            Cart
        </a>
    </div>


    @if ($items['data'])
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            @foreach ($items['data'] as $item)
                <div class="col">
                    <div class="card">
                        <img class="card-img-top"
                            src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/elements/2.jpg"
                            alt="Card image cap" />
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">{{ $item->name_item }}</h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    <p class="card-text">
                                        <span class="badge bg-label-success">Sisa budget &nbsp<span
                                                class="{{ checkPersen($item->quota, $item->remaining_quota) }}"
                                                class="badge bg-label-success" id="remainingQuota">{{ $item->remaining_quota }}</span> {{$item->satuan}}
                                    </p>
                                </div>
                            </div>
                            <p class="card-text">{{ $item->note }}</p>
                            <p class="card-text mt-4 mb-2">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="number" class="form-control mb-2" id="inputadd{{ $item->code_item }}"
                                        name="jml" min="1" max="{{ $item->remaining_quota }}" placeholder="Jumlah {{ $item->satuan_oca }}" required>
                                    <small><span>1 {{ $item->satuan }} = {{ round(1.0 / $item->convert, 3) }}
                                            {{ $item->satuan_oca }}</span></small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a id="btnadd{{ $item->code_item }}"href="javascript:addItem('{{ $item->code_item }}')"
                                        class="btn btn-outline-primary">Add</a>
                                </div>
                            </div>
                            </p>

                            <br>


                        </div>

                    </div>

                </div>
            @endforeach

        </div>
    @else
        <div class="row m-5 d-flex justify-content-center">
                No budget, Please contact PPIC to add budget to your department
        </div>
    @endif

    <div class="pagination">
        @foreach ($items['links'] as $key => $item)
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
        function addItem(code) {
            //simple http post request vanilla js
            //if input empty and not number
            if (document.getElementById("inputadd" + code).value == "") {
                alert("Jumlah tidak boleh kosong");
                return;
            }
            var jumlah = document.getElementById("inputadd" + code).value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('user.requestAddItem') }}", true);
            //csrf token
            //content type
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhr.send("code=" + code + "&jumlah=" + jumlah);

            xhr.onloadend = function() {
                if (xhr.status == 200) {

                    document.getElementById("btnadd" + code).classList.remove("btn-outline-primary");
                    document.getElementById("btnadd" + code).classList.add("btn-outline-success");
                    document.getElementById("btnadd" + code).innerHTML = "Added";
                    document.getElementById("remainingQuota").innerHTML -= jumlah;
                    //disable it
                    document.getElementById("btnadd" + code).disabled = true;
                    //change back after 2 seconds
                    setTimeout(function() {
                        document.getElementById("btnadd" + code).classList.remove("btn-outline-success");
                        document.getElementById("btnadd" + code).classList.add("btn-outline-primary");
                        document.getElementById("btnadd" + code).innerHTML = "Add";
                        document.getElementById("btnadd" + code).disabled = false;
                    }, 2000);

                } else {
                    alert(xhr.responseText);
                }
            };


        }
    </script>
@endsection
