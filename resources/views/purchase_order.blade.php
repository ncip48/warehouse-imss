@extends('layouts.main')
@section('title', __('Purchase Order'))
@section('custom-css')
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-po"
                        onclick="addPo()"><i class="fas fa-plus"></i> Add New PO</button>
                    {{-- <div class="card-tools">
                        <form>
                            <div class="input-group input-group">
                                <input type="text" class="form-control" name="q" placeholder="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table" class="table table-sm table-bordered table-hover table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>No.</th>
                                    <th>{{ __('No PO') }}</th>
                                    <th>{{ __('Proyek') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Tanggal PO') }}</th>
                                    <th>{{ __('Batas Akhir PO') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($purchases) > 0)
                                    @foreach ($purchases as $key => $d)
                                        @php
                                            $data = [
                                                'id' => $d->id,
                                                'no' => $purchases->firstItem() + $key,
                                                'vid' => $d->vendor_id,
                                                'nama_vendor' => $d->vendor_name,
                                                'nama_proyek' => $d->proyek_name,
                                                'no_po' => $d->no_po,
                                                'tgpo' => date('d/m/Y', strtotime($d->tanggal_po)),
                                                'btpo' => date('d/m/Y', strtotime($d->batas_po)),
                                            ];
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $data['no'] }}</td>
                                            <td>{{ $data['no_po'] }}</td>
                                            <td class="text-center">{{ $data['nama_proyek'] }}</td>
                                            <td class="text-center">{{ $data['nama_vendor'] }}</td>
                                            <td class="text-center">{{ $data['tgpo'] }}</td>
                                            <td class="text-center">{{ $data['btpo'] }}</td>
                                            <td class="text-center">
                                                <button title="Edit PO" type="button" class="btn btn-success btn-xs"
                                                    data-toggle="modal" data-target="#add-po"
                                                    onclick="editPo({{ json_encode($data) }})"><i
                                                        class="fas fa-edit"></i></button>

                                                <button title="Lihat Detail" type="button" data-toggle="modal"
                                                    data-target="#detail-po" class="btn-lihat btn btn-info btn-xs"
                                                    data-detail="{{ json_encode($data) }}"><i
                                                        class="fas fa-list"></i></button>
                                                        
                                                @if (Auth::user()->role == 0)
                                                    <button title="Hapus PO" type="button" class="btn btn-danger btn-xs"
                                                        data-toggle="modal" data-target="#delete-po"
                                                        onclick="deletePo({{ json_encode($data) }})"><i
                                                            class="fas fa-trash"></i></button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="5">{{ __('No data.') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                {{ $purchases->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

    {{-- modal tambah --}}
    <div class="modal fade" id="add-po">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Add New PO') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="save" action="{{ route('purchase_order.store') }}" method="post">
                        @csrf
                        <input type="hidden" id="save_id" name="id">
                        <div class="form-group row">
                            <label for="no_po" class="col-sm-4 col-form-label">{{ __('Nomor PO') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no_po" name="no_po">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="vendor_id" class="col-sm-4 col-form-label">{{ __('Vendor') }} </label>
                            <div class="col-sm-8">
                                {{-- <input type="text" class="form-control" id="vendor_id" name="vendor_id"> --}}
                                <select class="form-control" id="vendor_id" name="vendor_id">
                                    <option value="">Pilih Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="tanggal_po" class="col-sm-4 col-form-label w-50">{{ __('Tanggal PO') }} </label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control w-50" id="tanggal_po" name="tanggal_po">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="batas_po" class="col-sm-4 col-form-label">{{ __('Batas Akhir PO') }} </label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control w-50" id="batas_po" name="batas_po">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="incoterm" class="col-sm-4 col-form-label">{{ __('Incoterm') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="incoterm" name="incoterm">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pr_no" class="col-sm-4 col-form-label">{{ __('No PR') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pr_no" name="pr_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ref_sph" class="col-sm-4 col-form-label">{{ __('Referensi SPH') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ref_sph" name="ref_sph">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_just" class="col-sm-4 col-form-label">{{ __('No Justifikasi') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no_just" name="no_just">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_nego" class="col-sm-4 col-form-label">{{ __('No Nego') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="no_nego" name="no_nego">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ref_po" class="col-sm-4 col-form-label">{{ __('Refernsi Po') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="ref_po" name="ref_po">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="term_pay" class="col-sm-4 col-form-label">{{ __('Termin Pembayaran') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="term_pay" name="term_pay">
                                {{-- <select class="form-control" id="term_pay" name="term_pay">
                                    <option value="">Pilih Termin Pembayaran</option>
                                    <option value="0">Cash</option>
                                    <option value="1">Credit</option>
                                </select> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="garansi" class="col-sm-4 col-form-label">{{ __('Garansi') }} </label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="garansi" name="garansi">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="proyek_id" class="col-sm-4 col-form-label">{{ __('Proyek') }} </label>
                            <div class="col-sm-8">
                                {{-- <input type="date" class="form-control" id="proyek_id" name="proyek_id"> --}}
                                <select class="form-control" name="proyek_id" id="proyek_id">
                                    <option value="">Pilih Proyek</option>
                                    @foreach ($proyeks as $proyek)
                                        <option value="{{ $proyek->id }}">{{ $proyek->nama_proyek }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="catatan_vendor" class="col-sm-4 col-form-label">{{ __('Catatan Vendor') }} </label>
                            <div class="col-sm-8">
                                {{-- <input type="textarea" class="form-control" id="catatan_vendor" name="catatan_vendor"> --}}
                                <textarea class="form-control" name="catatan_vendor" id="catatn_vendor" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button id="button-save" type="button" class="btn btn-primary"
                        onclick="document.getElementById('save').submit();">{{ __('Tambahkan') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal detail --}}
    <div class="modal fade" id="detail-po">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Detail Purchase Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12" id="container-form">
                                <button id="button-cetak-sjn" type="button" class="btn btn-primary"
                                    onclick="document.getElementById('cetak-po').submit();">{{ __('Cetak') }}</button>
                                <table class="align-top w-100">
                                    <tr>
                                        <td style="width: 8%;"><b>No Surat</b></td>
                                        <td style="width:2%">:</td>
                                        <td style="width: 55%"><span id="no_po"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Proyek</b></td>
                                        <td>:</td>
                                        <td><span id="proyek_id"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Vendor</b></td>
                                        <td>:</td>
                                        <td><span id="vendor_id"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Tanggal PO</b></td>
                                        <td>:</td>
                                        <td><span id="tanggal_po"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Batas PO</b></td>
                                        <td>:</td>
                                        <td><span id="batas_po"></span></td>
                                    </tr>
                                    <tr>
                                        <td><b>Detail</b></td>
                                        <input type="hidden" name="sjn_id" id="sjn_id">
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <button id="button-tambah-produk" type="button" class="btn btn-info"
                                                onclick="showAddProduct()">{{ __('Tambah Item Detail') }}</button>
                                        </td>
                                    </tr>
                                </table>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>Item</th>
                                            <th>Kode Material</th>
                                            <th>Batas Akhir Diterima</th>
                                            <th>Kuantitas</th>
                                            <th>Unit</th>
                                            <th>Harga Per Unit</th>
                                            <th>Mata Uang</th>
                                            <th>Vat</th>
                                            <th>Total</th>
                                        </thead>

                                        <tbody id="table-products">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-0 d-none" id="container-product">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="input-group input-group-lg">
                                            <input type="text" class="form-control" id="pcode" name="pcode"
                                                min="0" placeholder="Product Code">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" id="button-check"
                                                    onclick="productCheck()">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="loader" class="card">
                                    <div class="card-body text-center">
                                        <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;"
                                            role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="form" class="card">
                                    <div class="card-body">
                                        <form role="form" id="stock-update" method="post">
                                            @csrf
                                            <input type="hidden" id="pid" name="pid">
                                            <input type="hidden" id="type" name="type">
                                            <div class="form-group row">
                                                <label for="pname"
                                                    class="col-sm-4 col-form-label">{{ __('Nama Barang') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="pname"
                                                        disabled>
                                                    <input type="hidden" class="form-control" id="product_id"
                                                        disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="no_nota"
                                                    class="col-sm-4 col-form-label">{{ __('QTY') }}</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="stock"
                                                        name="stock">
                                                </div>
                                            </div>
                                        </form>
                                        <button id="button-update-sjn" type="button" class="btn btn-primary w-100"
                                            onclick="sjnProductUpdate()">{{ __('Tambahkan') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal fade" id="delete-po">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modal-title" class="modal-title">{{ __('Delete PO') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" id="delete" action="{{ route('purchase_order.destroy') }}" method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="delete_id" name="id">
                    </form>
                    <div>
                        <p>Anda yakin ingin menghapus purchase order <span id="pcode" class="font-weight-bold"></span>?
                        </p>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Batal') }}</button>
                    <button id="button-save" type="button" class="btn btn-danger"
                        onclick="document.getElementById('delete').submit();">{{ __('Ya, hapus') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('custom-js')
    <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(function() {
            var user_id;
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });

        function resetForm() {
            $('#save').trigger("reset");
            $('#barcode_preview_container').hide();
        }

        function addPo() {
            $('#modal-title').text("Add Purchase Order");
            $('#button-save').text("Tambahkan");
            resetForm();
        }

        function editPo(data) {
            $('#modal-title').text("Edit PO");
            $('#button-save').text("Simpan");
            resetForm();
            $('#save_id').val(data.id);
            $('#no_po').val(data.no_po);
            $('#vendor_id').val(data.vendor_id);
            $('#vendor_id').find('option').each(function() {
                if ($(this).val() == data.vid) {
                    console.log($(this).val());
                    $(this).attr('selected',true);
                }
            });
            var date = data.tgpo.split('/');
            var newDate = date[2] + '-' + date[1] + '-' + date[0];
            $('#tanggal_po').val(newDate);
            var date = data.btpo.split('/');
            var newDate = date[2] + '-' + date[1] + '-' + date[0];
            $('#batas_po').val(newDate);
            $('#incoterm').val(data.incoterm);
            $('#pr_no').val(data.pr_no);
            $('#ref_sph').val(data.ref_sph);
            $('#no_just').val(data.no_just);
            $('#no_nego').val(data.no_nego);
            $('#ref_po').val(data.ref_po);
            $('#term_pay').val(data.term_pay);
            $('#garansi').val(data.garansi);
            $('#proyek_id').find('option').each(function() {
                if ($(this).val() == data.proyek_id) {
                    console.log($(this).val());
                    $(this).attr('selected',true);
                }
            });
            $('#catatan_vendor').val(data.catatan_vendor);
        }

        function deletePo(data) {
            $('#delete_id').val(data.id);
        }

        function download(type) {
            window.location.href = "{{ route('products.wip.history') }}?search={{ Request::get('search') }}&dl=" + type;
        }
    </script>
    @if (Session::has('success'))
        <script>
            toastr.success('{!! Session::get('success') !!}');
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.error('{!! Session::get('error') !!}');
        </script>
    @endif
    @if (!empty($errors->all()))
        <script>
            toastr.error('{!! implode('', $errors->all('<li>:message</li>')) !!}');
        </script>
    @endif
@endsection