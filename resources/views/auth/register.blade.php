@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register User') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">{{ __('Role') }}</label>

                            <div class="col-md-6">
                                <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required onchange="toggleRoleFields()">
                                    <option value="">{{ __('Select Role') }}</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                                </select>

                                @error('role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Dosen specific fields --}}
                        <div id="dosenFields" style="display: none;">
                            <div class="row mb-3">
                                <label for="nip" class="col-md-4 col-form-label text-md-end">{{ __('NIP') }}</label>
                                <div class="col-md-6">
                                    <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}">
                                    @error('nip')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat_dosen" class="col-md-4 col-form-label text-md-end">{{ __('Alamat') }}</label>
                                <div class="col-md-6">
                                    <textarea id="alamat_dosen" class="form-control @error('alamat_dosen') is-invalid @enderror" name="alamat_dosen">{{ old('alamat_dosen') }}</textarea>
                                    @error('alamat_dosen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nohp_dosen" class="col-md-4 col-form-label text-md-end">{{ __('No HP') }}</label>
                                <div class="col-md-6">
                                    <input id="nohp_dosen" type="text" class="form-control @error('nohp_dosen') is-invalid @enderror" name="nohp_dosen" value="{{ old('nohp_dosen') }}">
                                    @error('nohp_dosen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Mahasiswa specific fields --}}
                        <div id="mahasiswaFields" style="display: none;">
                            <div class="row mb-3">
                                <label for="nim" class="col-md-4 col-form-label text-md-end">{{ __('NIM') }}</label>
                                <div class="col-md-6">
                                    <input id="nim" type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim') }}">
                                    @error('nim')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="alamat_mahasiswa" class="col-md-4 col-form-label text-md-end">{{ __('Alamat') }}</label>
                                <div class="col-md-6">
                                    <textarea id="alamat_mahasiswa" class="form-control @error('alamat_mahasiswa') is-invalid @enderror" name="alamat_mahasiswa">{{ old('alamat_mahasiswa') }}</textarea>
                                    @error('alamat_mahasiswa')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nohp_mahasiswa" class="col-md-4 col-form-label text-md-end">{{ __('No HP') }}</label>
                                <div class="col-md-6">
                                    <input id="nohp_mahasiswa" type="text" class="form-control @error('nohp_mahasiswa') is-invalid @enderror" name="nohp_mahasiswa" value="{{ old('nohp_mahasiswa') }}">
                                    @error('nohp_mahasiswa')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="semester" class="col-md-4 col-form-label text-md-end">{{ __('Semester') }}</label>
                                <div class="col-md-6">
                                    <select id="semester" class="form-control @error('semester') is-invalid @enderror" name="semester">
                                        <option value="">{{ __('Select Semester') }}</option>
                                        @for($i = 1; $i <= 8; $i++)
                                            <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('semester')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="id_golongan" class="col-md-4 col-form-label text-md-end">{{ __('Golongan') }}</label>
                                <div class="col-md-6">
                                    <select id="id_golongan" class="form-control @error('id_golongan') is-invalid @enderror" name="id_golongan">
                                        <option value="">{{ __('Select Golongan') }}</option>
                                        @foreach(\App\Models\Golongan::all() as $golongan)
                                            <option value="{{ $golongan->id_golongan }}" {{ old('id_golongan') == $golongan->id_golongan ? 'selected' : '' }}>
                                                {{ $golongan->nama_gol }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_golongan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    const dosenFields = document.getElementById('dosenFields');
    const mahasiswaFields = document.getElementById('mahasiswaFields');
    
    // Hide all fields first
    dosenFields.style.display = 'none';
    mahasiswaFields.style.display = 'none';
    
    // Show relevant fields based on role
    if (role === 'dosen') {
        dosenFields.style.display = 'block';
    } else if (role === 'mahasiswa') {
        mahasiswaFields.style.display = 'block';
    }
}

// Show fields on page load if role is already selected
document.addEventListener('DOMContentLoaded', function() {
    toggleRoleFields();
});
</script>
@endsection