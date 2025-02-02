@extends('layouts.app-admin')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header bg-primary">
                    <h6 class="text-white mb-0">Pengaturan Aplikasi</h6>
                </div>

                <div class="card-body">

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Aplikasi</label>
                                    <input type="text" class="form-control bg-white border px-2 @error('app_name') is-invalid @enderror" 
                                           name="app_name" value="{{ old('app_name', $setting->app_name) }}">
                                    @error('app_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control bg-white border px-2 @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $setting->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nomor Telepon</label>
                                    <input type="text" class="form-control bg-white border px-2 @error('phone') is-invalid @enderror" 
                                           name="phone" value="{{ old('phone', $setting->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Logo Aplikasi</label>
                                    @if($setting->app_logo)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($setting->app_logo) }}" alt="Logo" class="img-fluid" style="max-height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control bg-white border px-2 @error('app_logo') is-invalid @enderror" 
                                           name="app_logo">
                                    @error('app_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Favicon</label>
                                    @if($setting->favicon)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($setting->favicon) }}" alt="Favicon" class="img-fluid" style="max-height: 32px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control bg-white border px-2 @error('favicon') is-invalid @enderror" 
                                           name="favicon">
                                    @error('favicon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Alamat Kantor</label>
                                    <textarea class="form-control bg-white border px-2 @error('office_address') is-invalid @enderror" 
                                              name="office_address" rows="3">{{ old('office_address', $setting->office_address) }}</textarea>
                                    @error('office_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Teks Footer</label>
                                    <textarea class="form-control bg-white border px-2 @error('footer_text') is-invalid @enderror" 
                                              name="footer_text" rows="2">{{ old('footer_text', $setting->footer_text) }}</textarea>
                                    @error('footer_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
