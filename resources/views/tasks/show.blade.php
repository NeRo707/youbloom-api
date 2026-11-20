@extends('layouts.app')

@section('title', 'Task Details: ' . $task->title)

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent p-0 mb-4">
        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}" class="text-danger">Tasks</a></li>
        <li class="breadcrumb-item active" aria-current="page">Task Details</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4 {{ $task->isCompleted() ? 'task-completed' : 'task-pending' }}">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark">{{ $task->title }}</h5>
                @if($task->isCompleted())
                <span class="badge badge-success badge-status py-2 px-3"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                @else
                <span class="badge badge-warning badge-status text-dark py-2 px-3"><i class="fas fa-clock mr-1"></i> Pending</span>
                @endif
            </div>

            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted font-weight-bold" style="font-size: 0.8rem;">Description</h6>
                <div class="p-3 bg-light rounded border mb-4">
                    @if($task->description)
                    <p class="mb-0 text-dark" style="white-space: pre-line;">{{ $task->description }}</p>
                    @else
                    <p class="text-muted font-italic mb-0">No description provided for this task.</p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.7rem;">Created At</small>
                        <span class="font-weight-bold text-dark">{{ $task->created_at->format('M d, Y h:i A') }}</span>
                        <small class="text-muted d-block">({{ $task->created_at->diffForHumans() }})</small>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 0.7rem;">Last Updated</small>
                        <span class="font-weight-bold text-dark">{{ $task->updated_at->format('M d, Y h:i A') }}</span>
                        <small class="text-muted d-block">({{ $task->updated_at->diffForHumans() }})</small>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light py-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary px-3">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Tasks
                </a>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-primary px-3">
                    <i class="fas fa-pencil-alt mr-1"></i> Edit Task
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
