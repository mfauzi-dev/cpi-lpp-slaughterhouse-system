@extends('layouts.master')

@section('title', 'Tambah Material')

@section('content')
    <div class="page-section">
        <div class="section-header">
            <h1>Material</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('operator.material.index') }}">
                        Material
                    </a>
                </div>
                <div class="breadcrumb-item active">
                    Tambah Material
                </div>
            </div>
        </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Periksa kembali data yang diinput.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('operator.material.store') }}" method="POST" id="form-material">
                @csrf

                <div class="card card-accent mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Material
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="filter-label">
                                Material Code
                            </label>
                            <input type="text" name="material_code"
                                class="form-control @error('material_code') is-invalid @enderror"
                                placeholder="Masukkan kode material"
                                value="{{ old('material_code') }}">

                            @error('material_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="filter-label">
                                Material Description
                            </label>
                            <input type="text" name="material_description"
                                class="form-control @error('material_description') is-invalid @enderror"
                                placeholder="Masukkan deskripsi material"
                                value="{{ old('material_description') }}">

                            @error('material_description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="section-info mb-2 mb-md-0">
                                <i class="fas fa-info-circle"></i>
                                Pastikan data Material sudah sesuai sebelum disimpan.
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan
                                </button>

                                <a href="{{ route('operator.material.index') }}" class="btn btn-ghost">
                                    <i class="fas fa-arrow-left mr-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection