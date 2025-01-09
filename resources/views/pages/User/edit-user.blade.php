@extends('index')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h4 class="">Edit User</h4>
                        <hr class="bg-dark px-auto">
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 ps-4 me-4">
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name" class="form-label text-sm required-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    value="{{ $user->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label text-sm required-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label text-sm required-label">Level</label>
                                <select class="form-select @error('level') is-invalid @enderror" id="level" name="level">
                                    <option value="admin" @if ($user->level == 'admin') selected @endif>Admin</option>
                                    <option value="petugas 1" @if ($user->level == 'petugas 1') selected @endif>Petugas 1</option>
                                    <option value="petugas 2" @if ($user->level == 'petugas 2') selected @endif>Petugas 2</option>
                                    <option value="petugas 3" @if ($user->level == 'petugas 3') selected @endif>Petugas 3</option>
                                    <option value="guru" @if ($user->level == 'guru') selected @endif>Guru</option>
                                    <option value="siswa" @if ($user->level == 'siswa') selected @endif>Siswa</option>
                                </select>
                                @error('level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="current_password" class="form-label text-sm required-label">Password Lama</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label text-sm required-label">Password Baru (Biarkan kosong jika tidak berubah)</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <a href="{{ route('user.index') }}" class="btn bg-gradient-danger">Kembali</a>
                            <button type="submit" class="btn bg-gradient-success float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
