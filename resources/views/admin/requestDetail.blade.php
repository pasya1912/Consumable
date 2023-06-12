@extends('layouts.master.main')

@section('content')
    <style>
        th,
        td {
            border-bottom: 1px solid #ddd;
        }

        .export {
            display: flex;
            position: relative;

        }

        .export>a {
            position: absolute;
            right: 0;
            bottom: 0;
        }
    </style>
    <div class="row">
        <div class="col">
            <div class="row">
                <h2><strong>Request Detail</strong></h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card" style="padding: 2rem;">
                <div class="row">
                    <p>
                        Status:
                        @if ($reqDetail->status == 'approved')
                        <span class="badge bg-success text-dark ">{{ $reqDetail->status }}</span>
                    @elseif($reqDetail->status == 'rejected')
                        <span class="badge bg-danger text-dark ">{{ $reqDetail->status }}</span>
                    @elseif($reqDetail->status == 'revised')
                        <span class="badge bg-dark text-light ">{{ $reqDetail->status }}</span>
                    @elseif($reqDetail->status == 'canceled')
                        <span class="badge bg-warning text-dark ">{{ $reqDetail->status }}</span>
                    @elseif($reqDetail->status == 'wait')
                        <span class="badge bg-light text-dark ">{{ $reqDetail->status }}</span>
                    @endif
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.requestUpdate', ['id' => $reqDetail->id]) }}" method="POST"
                            class="">
                            @csrf
                            <div class="mb-3">
                                <label for="defaultSelect" class="form-label">Update Status</label>
                                <select id="defaultSelect" class="form-select" name="status">
                                    <option>Pilih salah satu</option>

                                    <option value="rejected" @if($reqDetail->status =='rejected') disabled @endif>Rejected</option>
                                    <option value="approved" @if($reqDetail->status =='approved') disabled @endif>Approved</option>
                                    <option value="revised" @if($reqDetail->status =='revised') disabled @endif>Revised</option>
                                    <option value="canceled" @if($reqDetail->status =='canceled') disabled @endif>Canceled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning">Update</button>
                        </form>
                    </div>
                    <div class="col-md-6 export mr-2">
                        <div class="w-100  d-flex align-items-end gap-2 justify-content-end">

                            <a href="{{ route('admin.requestPrintGenerate', ['id' => $reqDetail->id]) }}"
                                class="btn btn-warning ">Generate</a>
                        <a href="{{ route('admin.requestPrint', ['id' => $reqDetail->id]) }}"
                            class="btn btn-warning ">Print</a>
                        </div>
                    </div>

                </div>
                <br>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center">Code</th>
                                <th style="text-align: center">Name</th>
                                <th style="text-align: center">Jumlah</th>
                                <th style="text-align: center">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reqDetail->items as $key => $item)
                                <tr>
                                    <td style="text-align: center">{{ $item->code_item }}</td>
                                    <td style="text-align: center">
                                        {{ $item->name_item }}
                                    </td>
                                    <td style="text-align: center">

                                        <input type="number" oninput="updateJumlah({{ $item->id }})"
                                        class="form-control" id="jumlah-{{ $item->id }}"
                                        value="{{ $item->jumlah }}" aria-describedby="" min="1"     name="jumlah" />
                                    </td>
                                    <td style="text-align: center">

                                            <input type="text" oninput="updateNote({{ $item->id }})"
                                                class="form-control" id="note-{{ $item->id }}"
                                                value="{{ $item->admin_note }}" aria-describedby="" name="admin_note" />


                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <br>
                <div class="mb-3 w-50">
                    <label for="defaultFormControlInput" class="form-label">Nama Pengambil</label>
                    <input type="text" class="form-control" id="defaultFormControlInput"
                        placeholder="{{ $reqDetail->nama }}" aria-describedby="defaultFormControlHelp" disabled />
                </div>
                <div class="mb-3 w-50">
                    <label for="defaultFormControlInput" class="form-label">Jam Pengambilan</label>
                    <input type="text" class="form-control" id="defaultFormControlInput"
                        placeholder="{{ $reqDetail->jam_pengambilan }}" aria-describedby="defaultFormControlHelp"
                        disabled />
                </div>
                <div class="mb-3 w-50">
                    <label for="defaultFormControlInput" class="form-label">Tanggal Pengambilan</label>
                    <input type="text" class="form-control" id="defaultFormControlInput"
                        placeholder="{{ $reqDetail->tanggal }}" aria-describedby="defaultFormControlHelp" disabled />
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function updateNote(id) {
            var note = $('#note-' + id).val();
            var data = {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "data": {
                            'admin_note': note
                        }
                    };
            $.ajax({
                url: "{{ url()->current() }}",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    console.log(xhr.message);
                }
            });


        }
        function updateJumlah(id) {
            var note = $('#jumlah-' + id).val();
            var data = {
                        "_token": "{{ csrf_token() }}",
                        "id": id,
                        "data": {
                            'jumlah': note
                        }
                    };
            $.ajax({
                url: "{{ url()->current() }}",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function(response) {
                    console.log(response.message);
                },
                error: function(xhr) {
                    console.log(xhr.message);
                }
            });


        }
    </script>
@endsection
