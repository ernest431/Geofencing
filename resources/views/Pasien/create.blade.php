@extends('layouts.master')

<!-- Header Tabel Pasien -->
@section('header')
  <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Input Pasien</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Pasien</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pasien</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

<!-- Content -->
@section('content')
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h3 class="mb-0 card-title">Form Input Pasien</h3>
          <a href="{{ url('/pasien') }}" class="btn btn-primary"><i class="ni ni-bold-left"></i><b>Kembali</b></a>
        </div>
        <div class="card-body">
           <!-- Light table -->
           <form method="post">
                {{ csrf_field() }}

                <!-- ID Pasien -->
                <div class="row">
                    <div class="col-md-3">
                        <label for="ID_Pasien">ID Pasien</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" name="ID_Pasien" id="ID_Pasien" placeholder="Ganti dengan ID Pasien Otomatis" class="form-control" readonly/>
                        </div>
                    </div>
                </div>

                <!-- Nama Pasien -->
                <div class="row">
                    <div class="col-md-3">
                    <label for="Nama_Pasien">Nama Pasien</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input id="Nama_Pasien" id="Nama_Pasien" class="form-control" placeholder="Nama Pasien" type="text">
                            <!-- <div class="input-group mb-4"> -->
                                <!-- <div class="input-group-append">
                                    <span class="input-group-text"><i class="ni ni-zoom-split-in"></i></span>
                                </div> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="row">
                    <div class="col-md-3">
                        <label for="Jenis_Kelamin">Jenis Kelamin</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <select name="Jenis_Kelamin" id="" class="form-control">
                                <option disabled selected>Pilih</option>
                                <option value="1">Laki - laki</option>
                                <option value="2">Perempuan</option>
                            </select>
                        </div>
                        <!-- <div class="form-group has-danger">
                        </div> -->
                        <!-- <input type="email" placeholder="Error Input" class="form-control is-invalid" /> -->
                    </div>
                </div>

                <!-- No Telp -->
                <div class="row">
                    <div class="col-md-3">
                        <label for="No_Telp">Nomer Telepon</label>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="text" class="form-control" name="No_Telp" id="No_Telp">
                        </div>
                        <!-- <div class="form-group has-danger">
                        </div> -->
                        <!-- <input type="email" placeholder="Error Input" class="form-control is-invalid" /> -->
                    </div>
                </div>

                <!-- Submit -->
                <div class="row">
                    <div class="col-md-3">
                        &nbsp;
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <button type="button" class="btn btn-success"><i class="ni ni-fat-add"></i> <b>Tambah</b></button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    $(function(){
      $('#tabel_pasien').DataTable({
        responsive : true,

      });
    });
  </script>
@stop 
