@extends('layouts.master')

<!-- Header Tabel Pasien -->
@section('header')
  <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Tabel</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Tabel</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tabel</li>
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
            <div class="pull-left">
              <h3 class="mb-0 card-title">Tabel Pasien</h3>
            </div>
            <div class="pull-right">
              <button type="button" onclick="addData()" class="btn btn-success"><i class="ni ni-fat-add"></i><b>Tambah Data</b></button>
            </div>
            <!-- <a href="{{ url('/pasien/create') }}" class="btn btn-success"><i class="ni ni-fat-add"></i><b>Tambah Data</b></a> -->
        </div>
        <div class="card-body">
           <!-- Light table -->
           <div class="table-responsive">
              <table id="tabel_pasien" class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">NO</th>
                    <th scope="col" class="sort" data-sort="name">ID Pasien</th>
                    <th scope="col" class="sort" data-sort="budget">Nama Pasien</th>
                    <th scope="col" class="sort" data-sort="status">Jenis Kelamin</th>
                    <th scope="col" class="sort" data-sort="completion">Nomer Telepon</th>
                  </tr>
                </thead>
                <tbody class="list">
                
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  @include('Pasien.modalPasien')

@endsection

@section('js')
  <script>
    // Pasien
    function addData()
    {
      save_method = "add";

      // Kosongkan Field
      $('#ID_Pasien').val('');
      $('#Nama_Pasien').val('');
      $('#Jenis_Kelamin').val('');
      $('#No_Telp').val('');

      // Remove Class
      // No Telp
      $('#No_Telp').removeClass('is-valid');
      $('#No_Telp').removeClass('is-invalid');

      // Nama Pasien
      $('#Nama_Pasien').removeClass('is-valid');
      $('#Nama_Pasien').removeClass('is-invalid');

      // ID Pasien
      $('#ID_Pasien').removeClass('is-valid');
      $('#ID_Pasien').removeClass('is-invalid');


      // Modal UP
      $('#pasienModal').modal('show');
    }

    var table = $('#tabel_pasien').DataTable({
                    responsive : true,
                    processing: true,
                    serverSide: true,
                    lengthChange: false,
                    searching: false,
                    ajax: "{{ url('/pasien') }}",
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'ID_Pasien', name: 'ID_Pasien' },                        
                        { data: 'Nama_Pasien', name: 'Nama_Pasien' },
                        { data: 'Jenis_Kelamin', name: 'Jenis_Kelamin' },
                        { data: 'Nomor_Telp', name: 'Nomor_Telp' },                     
                    ]
                  });

    // Tabel Pasien
    // Ajax insert pasien
    $('#modalSubmit').on('click', function(e){
      if(!e.isDefaultPrevented())
      {
        $.ajax({
          url : "{{ url('/pasien') }}",
          type : "POST",
          data : $('#formPasien').serialize(),
          success : function(data)
          {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: 'Data Pasien Berhasil Diinput!'
            })

            $('#pasienModal').modal('hide');

            table.ajax.reload();
          },
          error : function(error)
          {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Ada Kesalahan pada proses input data!',
            })
            table.ajax.reload();
          }
        });
      }
    });

    // Only Number
    $('.only-number').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    // Cek Value Form
    function cekValue(input)
    {
      // Proses
      if(input.length < 13)
      {
        $('#No_Telp').removeClass('is-valid');
        $('#No_Telp').addClass('is-invalid');
      }else{
        $('#No_Telp').removeClass('is-invalid');
        $('#No_Telp').addClass('is-valid');
      }
        
    }

    // Cek Value Nama
    function cekValueNama(input)
    {
      // Proses
      if(input.length < 4)
      {
        $('#Nama_Pasien').removeClass('is-valid');
        $('#Nama_Pasien').addClass('is-invalid');
      }else{
        $('#Nama_Pasien').removeClass('is-invalid');
        $('#Nama_Pasien').addClass('is-valid');
      }
    }

    // Cek Value Nama
    function cekValuePasien(input)
    {
      // Proses
      if(input.length < 4)
      {
        $('#ID_Pasien').removeClass('is-valid');
        $('#ID_Pasien').addClass('is-invalid');
      }else{
        $('#ID_Pasien').removeClass('is-invalid');
        $('#ID_Pasien').addClass('is-valid');
      }
    }


  </script>
@stop 
