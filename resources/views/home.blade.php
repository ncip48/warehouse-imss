@extends('layouts.main')
@section('title', __('Dashboard'))
{{-- @section('custom-css')
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
@endsection --}}
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid pb-5">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <a href="{{ route('surat_keluar.index') }}">
                        <div class="small-box bg-success">
                            <div class="inner" style="background-color: green;">
                                <p>Surat</p>
                                <h3>Keluar</h3>
                            </div>
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <hr class="mb-4" />
            <div class="row">
                @if (Auth::user()->role == 0 || Auth::user()->role == 4)
                    <div class="col-lg-3 col-6">
                        <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(1)">
                            <div class="small-box bg-success">
                                <div class="inner" style="background-color: goldenrod;">
                                    <p>Stock</p>
                                    <h3>In</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(0)">
                            <div class="small-box bg-info">
                                <div class="inner" style="background-color: blueviolet;">
                                    <p>Stock</p>
                                    <h3>Out</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="#" data-toggle="modal" data-target="#stock-form" onclick="stockForm(2)">
                            <div class="small-box bg-info">
                                <div class="inner" style="background-color: cadetblue;">
                                    <p>Product</p>
                                    <h3>Retur</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-undo"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('products.stock.history') }}">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <p>Stock</p>
                                    <h3>History</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-history"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('sjn') }}">
                            <div class="small-box bg-info">
                                <div class="inner" style="background-color: rgb(186, 226, 43);">
                                    <p>Surat</p>
                                    <h3>Jalan</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('spph.index') }}">
                            <div class="small-box bg-info">
                                <div class="inner" style="background-color: rgb(231, 48, 24);">
                                    <p>Surat Perminataan Penawaran</p>
                                    <h3>Harga</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-mail-bulk"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 5)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('purchase_order.index') }}">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <p>Purchase</p>
                                    <h3>Order</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.showPOPL') }}">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <p>Pembelian Langsung</p>
                                    <h3>Purchase Order</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 0 || Auth::user()->role == 1 || Auth::user()->role == 2 || Auth::user()->role == 3)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('purchase_request.index') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: coral">
                                    <p>Purchase</p>
                                    <h3>Request</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-cart-arrow-down"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 0 || Auth::user()->role == 1)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.tracking') }}">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <p>Purchase Request</p>
                                    <h3>Tracking</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-route"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 2 || Auth::user()->role == 3)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.trackingwil') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: #D988B9">
                                    <p>Purchase Request</p>
                                    <h3>Tracking Wilayah</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-route"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                @if (Auth::user()->role == 6)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('eng.purchase_request') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: #D988B9">
                                    <p>Edit</p>
                                    <h3>Purchase Request</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-route"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
                {{-- @if (Auth::user()->role == 0 || Auth::user()->role == 5)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.approvedPO') }}">
                            <div class="small-box bg-primary">

                                <div class="inner" style="background-color: #607274">
                                    <p>APRROVAL</p>
                                    <h3>PO</h3>
                                </div>
                                <div class="icon"><i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.aprrovedPO_PL') }}">
                            <div class="small-box bg-primary">

                                <div class="inner" style="background-color: #607274">
                                    <p>APRROVAL</p>
                                    <h3>PO/PL</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif --}}

                @if (Auth::user()->role == 0 || Auth::user()->role == 6)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.drawing.schematic') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #c8cd21"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Drawing</p>
                                    <h3>Schematic</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-drafting-compass"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('product.justifikasi') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Menu</p>
                                    <h3>Justifikasi</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('bom.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Menu</p>
                                    <h3>Bill Of Material</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- <div class="col-lg-3 col-6">
                        <a href="{{ route('product.justifikasi') }}">
                            <div class="small-box bg-primary">

                                <div class="inner" style="background-color: #607274">
                                    <p>Function</p>
                                    <h3>Control</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-gamepad"></i>
                                </div>
                            </div>
                        </a>
                    </div> --}}
                @endif

                @if (Auth::user()->role == 0 || Auth::user()->role == 7)
                    {{-- <div class="col-lg-3 col-6">
                        <a href="{{ route('product.drawing.schematic') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: #607274">
                                    <p>Data</p>
                                    <h3>Karyawan</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-restroom"></i>
                                </div>
                            </div>
                        </a>
                    </div> --}}
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('kode_aset.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Manajemen</p>
                                    <h3>Kode Aset</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('aset.index', ['type' => 1]) }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Manajemen</p>
                                    <h3>Aset</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('aset.index', ['type' => 2]) }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>Manajemen</p>
                                    <h3>Inventaris</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('penghapusan_aset.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #607274">
                                    <p>History</p>
                                    <h3>Penghapusan</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('karyawan.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #8709db">
                                    <p>Data</p>
                                    <h3>Karyawan</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('proyek.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #ff0000">
                                    <p>Proyek</p>
                                    <h3>Kereta Api</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-train"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('trainset.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #9d0769">
                                    <p>Master Data</p>
                                    <h3>Trainset</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-train"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('service.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #08faf6">
                                    <p>Service</p>
                                    <h3>Record</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-train"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('jadwal.index') }}">
                            <div class="small-box bg-primary">
                                {{-- <div class="inner" style="background-color: #afc124"> --}}
                                <div class="inner" style="background-color: #08faf6">
                                    <p>Master Data</p>
                                    <h3>Jadwal Perawatan</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-train"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->role == 0 || Auth::user()->role == 8)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('penerimaan_barang') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: #c8cd21">
                                    <p>Penerimaan</p>
                                    <h3>Barang</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if (Auth::user()->role == 0 || Auth::user()->role == 9)
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('lppb') }}">
                            <div class="small-box bg-primary">
                                <div class="inner" style="background-color: #c45421">
                                    <p>Laporan</p>
                                    <h3>LPPB</h3>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- modal --}}
        <div class="modal fade" id="stock-form">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modal-title" class="modal-title">{{ __('Stock In') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <img width="300px" src="{{ asset('img/scan.jpg') }}" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="pcode" name="pcode"
                                        min="0" placeholder="Product Code">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="button-check" onclick="productCheck()">
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
                                            <input type="text" class="form-control" id="pname" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_nota" class="col-sm-4 col-form-label">{{ __('No. SJN') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="no_nota" name="no_nota">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-4 col-form-label">{{ __('Nama') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pamount" class="col-sm-4 col-form-label">{{ __('Jumlah') }}</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="pamount" name="pamount"
                                                min="1" value="1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="shelf" class="col-sm-4 col-form-label">Lokasi</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" style="width: 100%;" id="shelf"
                                                name="shelf">
                                            </select>
                                        </div>
                                    </div>
                                    <div id="date" class="form-group row">
                                        <label for="stock_date" class="col-sm-4 col-form-label">Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group date" id="stock_date" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input stock_date_text"
                                                    id="stock_date_text" name="stock_date" data-target="#stock_date" />
                                                <div class="input-group-append" data-target="#stock_date"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="button-update" type="button" class="btn btn-primary"
                            onclick="stockUpdate()">{{ __('Stock In') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- coba SJN -->
        <div class="modal fade" id="stock-form1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="modal-title" class="modal-title">{{ __('SJN') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <img width="300px" src="{{ asset('img/scan.jpg') }}" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control" id="pcode" name="pcode"
                                        min="0" placeholder="Product Code">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" id="button-check" onclick="productCheck()">
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
                                            <input type="text" class="form-control" id="pname" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="no_nota" class="col-sm-4 col-form-label">{{ __('No. SJN') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="no_nota" name="no_nota">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="name"
                                            class="col-sm-4 col-form-label">{{ __('Spesifikasi') }}</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="pamount" class="col-sm-4 col-form-label">{{ __('Jumlah') }}</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="pamount" name="pamount"
                                                min="1" value="1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="shelf" class="col-sm-4 col-form-label">Lokasi</label>
                                        <div class="col-sm-8">
                                            <select class="form-control select2" style="width: 100%;" id="shelf"
                                                name="shelf">
                                            </select>
                                        </div>
                                    </div>
                                    <div id="date" class="form-group row">
                                        <label for="stock_date" class="col-sm-4 col-form-label">Date</label>
                                        <div class="col-sm-8">
                                            <div class="input-group date" id="stock_date" data-target-input="nearest">
                                                <input type="text"
                                                    class="form-control datetimepicker-input stock_date_text"
                                                    id="stock_date_text" name="stock_date" data-target="#stock_date" />
                                                <div class="input-group-append" data-target="#stock_date"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                        <button id="button-update" type="button" class="btn btn-primary"
                            onclick="stockUpdate()">{{ __('Stock In') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- coba SJN -->



    </section>
@endsection
@section('custom-js')
    {{-- <script src="/plugins/toastr/toastr.min.js"></script>
    <script src="/plugins/select2/js/select2.full.min.js"></script>
    <script src="/plugins/moment/moment.min.js"></script>
    <script src="/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script> --}}
    <script>
        $(function() {
            $('#form').hide();
            loader(0);
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            $('#stock_date').datetimepicker({
                viewMode: 'years',
                format: 'MM/DD/YYYY HH:mm:ss'
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $('#pcode').on('input', function() {
            $("#form").hide();
            $("#button-update").hide();
        });

        function resetForm() {
            $('#form').trigger("reset");
            $('#pcode').val('');
            $("#button-update").hide();
            $("#date").hide();
            $('#pcode').prop("disabled", false);
            $('#button-check').prop("disabled", false);
        }

        function stockForm(type = 1) {
            $("#form").hide();
            resetForm();
            $("#type").val(type);
            //remove #proyek_id first
            $('#form').find('.card-body').find('#proyek_id').parent().parent().remove();
            if (type == 0) {
                $('#modal-title').text("Stock Out");
                $('#button-update').text("Stock Out");
                $("#date").show();

                //find child in #form with class .card-body then append
                $('#form').find('.card-body').append(
                    '<div class="form-group row"><label for="proyek_id" class="col-sm-4 col-form-label">Keproyekan</label><div class="col-sm-8"><select class="form-control select2" style="width: 100%;" id="proyek_id" name="proyek_id"></select></div></div>'
                );

            } else if (type == 1) {
                $('#modal-title').text("Stock In");
                $('#button-update').text("Stock In");
                $("#date").show();
                //remove the proyek_id
                $('#form').find('.card-body').find('#proyek_id').parent().parent().remove();
            } else {
                $('#modal-title').text("Retur");
                $('#button-update').text("Retur");
                $("#date").hide();
                //remove the proyek_id
                $('#form').find('.card-body').find('#proyek_id').parent().parent().remove();
            }
        }

        function getProyek(val) {
            $.ajax({
                url: "{{ url('products/keproyekan') }}",
                type: "GET",
                data: {
                    "format": "json"
                },
                dataType: "json",
                success: function(data) {
                    $('#proyek_id').empty();
                    $('#proyek_id').append('<option value="">.:: Select Proyek::.</option>');
                    $.each(data, function(key, value) {
                        if (value.id == val) {
                            $('#proyek_id').append('<option value="' + value.id + '" selected>' + value
                                .nama_proyek + '</option>');
                        } else {

                            $('#proyek_id').append('<option value="' + value.id + '">' + value
                                .nama_proyek + '</option>');
                        }
                    });
                }
            });
        }

        function getShelf(pid = null) {
            var type = $('#type').val();
            $.ajax({
                url: "{{ url('/products/shelf') }}",
                type: "GET",
                data: {
                    "format": "json",
                    "product_id": pid
                },
                dataType: "json",
                success: function(data) {
                    $('#shelf').empty();
                    $('#shelf').append('<option value="">.:: Select Shelf ::.</option>');
                    $.each(data, function(key, value) {
                        if (type == 0) {
                            $('#shelf').append('<option value="' + value.shelf_id + '">' + value
                                .shelf_name + '</option>');
                        } else {
                            $('#shelf').append('<option value="' + value.shelf_id + '">' + value
                                .shelf_name + '</option>');
                        }
                    });
                }
            });
        }

        function enableStockInput() {
            $('#button-update').prop("disabled", false);
            $("#button-update").show();
            $('#form').show();
        }

        function disableStockInput() {
            $('#button-update').prop("disabled", true);
            $("#button-update").hide();
            $('#form').hide();
        }

        function loader(status = 1) {
            if (status == 1) {
                $('#loader').show();
            } else {
                $('#loader').hide();
            }
        }

        function productCheck() {
            var pcode = $('#pcode').val();
            if (pcode.length > 0) {
                loader();
                $('#form').hide();
                $('#pcode').prop("disabled", true);
                $('#button-check').prop("disabled", true);
                $.ajax({
                    url: "{{ url('/products/check/') }}" + "/" + pcode,
                    type: "GET",
                    data: {
                        "format": "json"
                    },
                    dataType: "json",
                    success: function(data) {
                        loader(0);
                        if (data.status == 1) {
                            $('#pid').val(data.data.product_id);
                            $('#pcode').val(data.data.product_code);
                            $('#pname').val(data.data.product_name);
                            if ($('#type').val() == 0) {
                                getShelf($('#pid').val());
                                getProyek();
                            } else {
                                getShelf();
                            }
                            enableStockInput();
                        } else {
                            disableStockInput();
                            toastr.error("Product Code tidak dikenal!");
                        }
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    },
                    error: function() {
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    }
                });
            } else {
                toastr.error("Product Code belum diisi!");
            }
        }

        function stockUpdate() {
            loader();
            $('#pcode').prop("disabled", true);
            $('#button-check').prop("disabled", true);
            $('#button-update').prop("disabled", true);
            disableStockInput();
            var data = {
                product_id: $('#pid').val(),
                name: $('#name').val(),
                no_nota: $('#no_nota').val(),
                amount: $('#pamount').val(),
                stock_date: $('#stock_date_text').val(),
                shelf: $('#shelf').val(),
                type: $('#type').val(),
                proyek_id: $('#proyek_id').val()
            }

            $.ajax({
                url: "{{ url('/products/stockUpdate') }}",
                type: "post",
                data: JSON.stringify(data),
                dataType: "json",
                contentType: 'application/json',
                success: function(data) {
                    loader(0);
                    if (data.status == 1) {
                        toastr.success(data.message);
                        resetForm();
                    } else {
                        toastr.error(data.message);
                        enableStockInput();
                        $('#pcode').prop("disabled", false);
                        $('#button-check').prop("disabled", false);
                    }
                },
                error: function() {
                    loader(0);
                    toastr.error("Unknown error! Please try again later!");
                    resetForm();
                }
            });
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
