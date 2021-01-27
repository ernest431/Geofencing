<!-- Modal -->
<div class="modal fade" id="pasienModal" tabindex="-1" role="dialog" aria-labelledby="pasienModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pasienModalLabel">Input Data Pasien</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" id="formPasien">
            {{ csrf_field() }}

            <!-- ID Pasien -->
            <div class="row">
                <div class="col-md-3">
                    <label for="ID_Pasien">ID Pasien</label>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="text" name="ID_Pasien" id="ID_Pasien" onkeyup="cekValuePasien(this.value)" placeholder="ID Pasien" class="form-control only-number" autocomplete="off" require>
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
                        <input id="Nama_Pasien" name="Nama_Pasien" class="form-control" onkeyup="cekValueNama(this.value)" autocomplete="off" placeholder="Nama Pasien" type="text" require>
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
                        <select name="Jenis_Kelamin" id="" class="form-control" autocomplete="off">
                            <option disabled selected>Pilih</option>
                            <option value="1">Laki - laki</option>
                            <option value="2">Perempuan</option>
                        </select>
                    </div>
                    <!-- <div class="form-group has-danger">
                        <input type="email" placeholder="Error Input" class="form-control is-invalid" />
                    </div> -->
                </div>
            </div>

            <!-- No Telp -->
            <div class="row">
                <div class="col-md-3">
                    <label for="No_Telp">Nomor Telepon</label>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="text" class="form-control only-number" minlength="12" maxlength="16" onkeyup="cekValue(this.value)" placeholder="Nomor Telepon" autocomplete="off" name="No_Telp" id="No_Telp" require>
                    </div>
                    <!-- <div class="form-group has-danger">
                    </div> -->
                    <!-- <input type="email" placeholder="Error Input" class="form-control is-invalid" /> -->
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" id="modalSubmit" class="btn btn-primary"><i class="fa fa-plus"></i> <b>Simpan</b></button>
      </div>
    </div>
  </div>
</div>