@extends('layouts.master.main',['title' => 'Dashboard'])

<?php
function checkPersen($quota,$remaining)
{
 if($remaining / $quota * 100.0 > 50.0){
    return 'text text-success';
 }
    else if($remaining / $quota * 100.0 > 10.0){
        return 'text text-warning';
    }
    else{
        return 'text text-danger';
    }
}

?>

@section('content')
<h6 class="pb-1 mb-4 text-muted">Grid Card</h6>
    <div class="demo-inline-spacing mb-5 text-end">
      <a href="{{route('user.request')}}" class="btn btn-info">
        Cart
      </a>
    </div>
<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
@if(isset($items['data']))
@foreach($items['data'] as $item)

    <div class="col">
        <div class="card h-100">
            <img class="card-img-top" src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/elements/2.jpg" alt="Card image cap" />
            <div class="card-body">
                <h5 class="card-title">{{$item->name_item}}</h5>
                <p class="card-text">{{$item->note}}</p>
                <div class="card accordion-item">
                    <h2 class="accordion-header" id="heading_{{$item->code_item}}">
                    <button type="button" class="accordion-button collapsed text text-center" data-bs-toggle="collapse" data-bs-target="#accordion_{{$item->code_item}}" aria-expanded="true" aria-controls="accordionOne">
                        <p class="card-text">Request</p>
                    </button>
                    </h2>

                    <div id="accordion_{{$item->code_item}}" class="accordion-collapse collapse" >
                      <div class="accordion-body">
                        <p class="card-text mt-2 mb-2">
                            <input type="number" id="inputadd{{$item->code_item}}" name="jml" placeholder="Jumlah {{$item->satuan_oca}}">
                            <br><small><span>1 {{$item->satuan}} = {{1.0 / $item->convert}} {{$item->satuan_oca}}</span></small>

                            <hr/>
                            <a  id="btnadd{{$item->code_item}}"href="javascript:addItem('{{$item->code_item}}')" class="btn btn-outline-primary">Add item</a>

                        </p>
                      </div>
                    </div>
                  </div>
                <p class="card-text">
                    <small class="text-muted">Sisa budget <span class="{{checkPersen($item->quota,$item->remaining_quota)}}">{{$item->remaining_quota}}</span> / <span class="text text-dark">{{$item->quota}}</span> {{$item->satuan_oca}}</small>
                  </p>

            </div>

        </div>

    </div>
@endforeach
@endif

</div>
<div class="pagination">
    @foreach($items['links'] as $key =>$item)
    @if($item['url'] != null)
    @if(!$item['active'])
        <a href="{{$item['url']}}"><?php echo $item['label'];?></a>
    @else
        <a class="active" href="{{$item['url']}}"><?php echo $item['label'];?></a>
    @endif
    @endif
    @endforeach
</div>
@endsection
@section('script')
<script>
    function addItem(code){
        //simple http post request vanilla js
        //if input empty and not number
        if(document.getElementById("inputadd"+code).value == "" ){
            alert("Jumlah tidak boleh kosong");
            return;
        }
        var jumlah = document.getElementById("inputadd"+code).value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST","{{route('user.requestAddItem')}}", true);
        //csrf token
        //content type
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-CSRF-TOKEN', "{{csrf_token()}}");
        xhr.send("code="+code+"&jumlah="+jumlah);

        xhr.onloadend = function () {
            if (xhr.status == 200) {

                document.getElementById("btnadd"+code).classList.remove("btn-outline-primary");
                document.getElementById("btnadd"+code).classList.add("btn-outline-success");
                document.getElementById("btnadd"+code).innerHTML = "Added";
                //disable it
                document.getElementById("btnadd"+code).enabled= false;
                //change back after 2 seconds
                setTimeout(function(){
                    document.getElementById("btnadd"+code).classList.remove("btn-outline-success");
                    document.getElementById("btnadd"+code).classList.add("btn-outline-primary");
                    document.getElementById("btnadd"+code).innerHTML = "Add item";
                    document.getElementById("btnadd"+code).enabled= true;
                }, 2000);

                                }
            else{
                alert(xhr.responseText);
            }
                };


    }
</script>
@endsection
