@extends('layouts.master.main')

@section('content')
    <div class="row">
        <div class="col">
            <div class="row">
                <h2><strong>Cart Request</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                    <div class="col-md-10"></div>
                </div>
                <div class="table-responsive">
                    <table class="table" cellpadding=10 cellspasing=15>
                        <thead>
                            <tr>
                                <th style="text-align: center">Code</th>
                                <th style="text-align: center">Name</th>
                                <th style="text-align: center">Jumlah</th>
                                <th style="text-align: center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arr['items'] as $key => $item)
                                <tr class="tables">
                                    <td style="text-align: center">{{ $item->code_item }}</td>
                                    <td style="text-align: center">{{ $item->name_item }}</td>
                                    <td style="text-align: center">
                                        <input class="form-control" id="inputRequest{{ $key }}" type="number"
                                            oninput="ubahJumlah({{ $key }},{{ $item->remaining_quota }})"
                                            value="{{ $arr['cart'][$key]['jumlah'] }}" /> Max: {{ $item->remaining_quota }}
                                        {{ $item->satuan_oca }}
                                    </td>
                                    <td style="text-align: center">
                                        <button class="btn rounded-pill me-2 btn-label-danger"
                                            onclick="hapusItem(this)">Hapus</button>
                                    </td>
                                </tr>
                        </tbody>
                        @endforeach
                        <div class="col">
                            <form action="{{ route('user.requestStore') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="defaultFormControlInput" class="form-label">Nama Pengambil Barang</label>
                                    <input type="text" name="nama_pj" class="form-control" id="defaultFormControlInput"
                                        placeholder="Nama Pengambil Barang" aria-describedby="defaultFormControlHelp"
                                        required />
                                </div>
                                <div class="mb-3">
                                    <label for="defaultSelect" class="form-label">Jadwal Pengambilan</label>
                                    <select name="jadwal" id="defaultSelect" class="form-select">
                                        @foreach ($arr['jadwal'] as $shift)
                                            <option value="{{ $shift->id }}">Shift {{ $shift->id }}
                                                ({{ $shift->awal }} - {{ $shift->akhir }})</option>
                                        @endforeach
                                    </select>
                                    <br><button type="submit" class="btn btn-warning">Request</button>
                            </form>
                        </div>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        function ubahJumlah(index, max) {
            var jumlah = document.querySelector("#inputRequest" + index).value;
            //not allow if jumlah > max
            if (jumlah > max) {
                alert("Jumlah melebihi batas");
                document.querySelector("#inputRequest" + index).value = max;
                return;
            }
            var code = document.getElementsByTagName("td")[index * 3].innerHTML;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("demo").innerHTML = this.responseText;
                    console.log(this.responseText);
                }
            };
            xhttp.open("POST", "/request/ubah-item/" + index + "", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhttp.send("action=update&jumlah=" + jumlah);
        }

        function hapusItem(element) {
            //call with vanila js ajax to route user.requestHapusItem
            const tables= document.getElementsByClassName("tables");
            //get current tr index
            var index = Array.prototype.indexOf.call(tables, element.parentNode.parentNode);
            //get code item
            console.log(index);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("demo").innerHTML = this.responseText;
                    console.log(this.responseText);
                    //remove current pressed button row
                    document.getElementsByTagName("tr")[index + 1].remove();

                }
            };
            xhttp.open("POST", "/request/ubah-item/" + index + "", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.setRequestHeader('X-CSRF-TOKEN', "{{ csrf_token() }}");
            xhttp.send("action=delete");

        }
    </script>
@endsection
