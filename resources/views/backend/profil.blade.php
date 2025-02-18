@extends('backend.layouts.main')
@section('title','Edit Profil')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2/select2-bootstrap-5-theme.min.css') }}" />
@endpush
@section('content')
<div class="container">
    <div class="page-inner">
      <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
          <h3 class="fw-bold mb-3">@yield('title')</h3> 
        </div>
      </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('profil.update', $user->id) }}" method="POST">
                                @csrf
                                <!-- Judul -->
                                {{-- <div class="form-group">
                                    <div class="col-lg-4 col-md-9 col-sm-8">
                                        <div class="input-file input-file-image">
                                            <img class="img-upload-preview img-circle" width="100" height="100" src="http://placehold.it/100x100" alt="upload profil">
                                            <input type="file" class="form-control form-control-file" id="avatar" name="avatar" accept=".png,.jpg,.jpeg">
                                            <label for="avatar" class="btn btn-primary btn-round btn-sm"><i class="fa fa-file-image"></i> Upload a Profil</label>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <label for="name">Nama <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name')
                                        is-invalid
                                    @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">
                                                {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email')
                                        is-invalid
                                    @enderror" id="email" name="email" value="{{ old('email',$user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">
                                                {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="name">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password')
                                        is-invalid
                                    @enderror" id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">
                                                {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
     
    </div>
</div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/plugin/select2/select2.full.min.js') }}"></script>

    <script>
        $('.select2').select2( {
            theme: 'bootstrap-5'
        });

    </script>
@endpush
