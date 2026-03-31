@extends('layouts.app')
@section('title', '{{ $title ?? "Page" }} - Tread CRM')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 fw-bold mb-0">
                <i class="fas fa-{{ $icon ?? "circle" }} me-2 text-primary"></i>{{ $title ?? "Page" }}
            </h2>
            <p class="text-muted mb-0">{{ $subtitle ?? "Management section coming soon..." }}</p>
        </div>
        <a href="#" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New {{ $title ?? "Item" }}
        </a>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card card-tread">
                <div class="card-body">
                    <div class="alert alert-info border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $title }}</strong> management ready! 
                        Add your data tables, forms, and CRUD here.
                    </div>
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-list fa-2x text-primary mb-2"></i>
                                    <h6>List View</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-plus-circle fa-2x text-success mb-2"></i>
                                    <h6>Create</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-edit fa-2x text-warning mb-2"></i>
                                    <h6>Edit</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card bg-light h-100">
                                <div class="card-body">
                                    <i class="fas fa-trash fa-2x text-danger mb-2"></i>
                                    <h6>Delete</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection