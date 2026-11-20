@extends('layouts.app')

@section('title', 'Create New Task')

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-4">
        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}" class="text-danger">Tasks</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create New Task</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom font-weight-bold">
                <i class="fas fa-plus text-danger mr-2"></i> Create New Task
            </div>

            <div class="card-body p-4">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="form-group mb-4">
                        <label for="title" class="font-weight-bold">Task Title <span class="text-danger">*</span></label>
                        <input type="text"
                            name="title"
                            id="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="e.g. Update user documentation"
                            value="{{ old('title') }}"
                            required
                            autofocus>
                        @error('title')
                        <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-4">
                        <label for="description" class="font-weight-bold">Task Description <span class="text-muted font-weight-normal">(Optional)</span></label>
                        <textarea name="description"
                            id="description"
                            rows="4"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Provide any additional details...">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold d-block">Task Status <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline mr-4">
                            <input type="radio" id="statusPending" name="status" value="Pending" class="custom-control-input" {{ old('status', 'Pending') === 'Pending' ? 'checked' : '' }} required>
                            <label class="custom-control-label" for="statusPending">
                                <span class="badge badge-warning badge-status text-dark mr-1"><i class="fas fa-clock mr-1"></i> Pending</span>
                            </label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="statusCompleted" name="status" value="Completed" class="custom-control-input" {{ old('status') === 'Completed' ? 'checked' : '' }} required>
                            <label class="custom-control-label" for="statusCompleted">
                                <span class="badge badge-success badge-status mr-1"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                            </label>
                        </div>
                        @error('status')
                        <div class="text-danger small font-weight-bold mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-danger font-weight-bold px-4">
                            <i class="fas fa-save mr-1"></i> Save Task
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
