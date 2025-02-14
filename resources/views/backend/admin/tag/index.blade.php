@extends('backend.layouts.main')
@section('title','Tags')
@section('content')
<div class="container">
    <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">@yield('title')</h3> 
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
            <span>Tambah Tag Baru</span>
           <form id="addTagForm">
                @csrf
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name">
                     <div class="invalid-feedback name-error"></div>
                </div>
                <div class="form-group">
                    <label for="name">Slug</label>
                    <input type="text" class="form-control" id="slug" name="slug" readonly>
                </div>
                <div class="form-group">
                    <button type="button" id="addTagBtn" class="btn btn-sm btn-primary"><i class="bi bi-floppy"></i> Simpan</button>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Tag</th>
                                    <th>Slug</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@include('backend.components.modal-delete')
@include('backend.admin.tag.edit')
@endsection
@push('js')
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script>
      $(document).ready(()=>{
        let datatables = $("#dataTables").DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                      type: "GET",
                      url: "{{ route('tags.datatable') }}",
                    },  
                    columns: [
                         {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                        },
                        { 
                            data: 'name', 
                            name: 'name',
                        },
                        { 
                            data: 'slug', 
                            name: 'slug',
                        },
                        {
                            data: 'aksi', 
                            name: 'aksi', 
                            orderable: false, 
                            searchable: false,
                        }
                    ],
                });

        function generateSlug(text) {
            return text
                .toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')  // Ganti karakter non-alfanumerik dengan strip
                .replace(/-+/g, '-')          // Ganti multiple strip dengan satu strip
                .replace(/^-|-$/g, '');       // Hapus strip di awal dan akhir
        }

        // Event listener untuk generate slug saat mengetik name
        $('#name').on('input', function() {
            let name = $(this).val();
            let slug = generateSlug(name);
            $('#slug').val(slug);
        });

       $('#addTagBtn').click(function(e) {
            e.preventDefault();
            // Reset error messages
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Disable submit button
            $('#addTagBtn').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
            );

            let data = {
                'name': $('#name').val(),
                'slug': $('#slug').val(),
            }

            $.ajax({
                type: 'POST',
                url: "{{ route('tags.store') }}",
                data: data,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Reset form
                    $('#addTagForm')[0].reset();
                    
                    // Tampilkan alert bootstrap
                     toastr.success("Tag berhasil disimpan!");
                    // Reload DataTable
                    datatables.ajax.reload(null, false);
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        
                        // Display validation errors
                        if (errors.name) {
                            $('#name').addClass('is-invalid');
                            $('.name-error').text(errors.name[0]);
                        }
                        
                        // Tampilkan alert error
                        toastr.error("Tag gagal disimpan!");
                    } else {
                        // Show generic error
                       toastr.error("Tag gagal disimpan!");
                    }
                },
                complete: function() {
                    // Re-enable submit button
                    $('#addTagBtn').prop('disabled', false).html(
                        '<span class="btn-label"></span> Add New Tag'
                    );
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            
            // Menyimpan ID yang akan dihapus ke dalam tombol di modal
            $('#btnDestroy').data('id', id);
            $('#confirmModalDelete').modal('show');
        });

        $('#btnDestroy').click(function(e){
            e.preventDefault();
            const id = $(this).data('id'); 

            $.ajax({
                type: "DELETE",
                url: `{{ route('tags.destroy','') }}/${id}`,
                headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (response) {
                     toastr.success("Tag berhasil dihapus!");

                    // Reload DataTable
                    datatables.ajax.reload(null, false);

                    $('#confirmModalDelete').modal('hide');
                },
                error: function (xhr) {
                      toastr.error("Tag gagal dihapus!");
                }
            });
        })

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            const id = $(this).data('id');

            // Hapus pesan error
            $('.invalid-feedback').text('');

            // Hapus class is-invalid
            $('.form-control').removeClass('is-invalid');

            // Reset form
            $('#formEditTag')[0].reset();
            
            // Ambil data kategori via AJAX
            $.ajax({
                url: `{{ route('tags.edit', '') }}/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Isi form modal
                        $('#editTagId').val(response.tag.id);
                        $('#editName').val(response.tag.name);
                        $('#editSlug').val(response.tag.slug);
                        
                        // Tampilkan modal
                        $('#modalTag').modal('show');
                    }
                },
                error: function() {
                    // Tampilkan pesan error
                    toastr.error("Terjadi kesalahan!");
                }
            });

        });

        $('#editName').on('input', function() {
            let name = $(this).val();
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9-]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
                $('#editSlug').val(slug);
        });

        // Event listener untuk tombol update
        $('#btnUpdate').on('click', function() {
            const id = $('#editTagId').val();

             $('#btnUpdate').prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updated...'
            );
            
            $.ajax({
                url: `{{ route('tags.update','') }}/${id}`,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: $('#formEditTag').serialize(),
                success: function(response) {
                    // Tutup modal

                  
                    $('#modalTag').modal('hide');
                    
                    // Reload DataTable
                    $('#dataTables').DataTable().ajax.reload(null, false);
                    
                    // Tampilkan alert sukses
                    toastr.success("Tag berhasil diupdate!");
                },
                error: function(xhr) {
                    // Tampilkan error validasi
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        
                        if (errors.name) {
                            $('#editName').addClass('is-invalid');
                            $('.name-error').text(errors.name[0]);
                        }
                    }
                    
                    // Tampilkan alert error
                    toastr.error("Tag gagal diupdate!");
                },
                complete: function() {
                    // Re-enable submit button
                    $('#btnUpdate').prop('disabled', false).html(
                        '<span class="btn-label"></span> Update'
                    );
                }
            });
        });         

      });

   
    </script>
@endpush
