@extends('layouts.master')

@section('title', 'Edit Material')

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
                    Edit Material
                </div>
            </div>
        </div>

        <div class="section-lead">
            Ubah informasi material sesuai data yang dibutuhkan.
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

            <form action="{{ route('operator.material.update', $material->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header">
                        <h4>
                            <span class="step-badge">1</span>
                            Informasi Material
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label class="filter-label">Material Code</label>
                            <input type="text" name="material_code"
                                class="form-control @error('material_code') is-invalid @enderror"
                                placeholder="Masukkan material code"
                                value="{{ old('material_code', $material->material_code) }}">

                            @error('material_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="filter-label">Material Description</label>
                            <textarea name="material_description" rows="3"
                                class="form-control @error('material_description') is-invalid @enderror"
                                placeholder="Masukkan material description">{{ old('material_description', $material->material_description) }}</textarea>

                            @error('material_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="section-info mb-2 mb-md-0">
                                <i class="fas fa-info-circle"></i>
                                Pastikan perubahan data Material sudah sesuai.
                            </div>

                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>
                                    Update
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