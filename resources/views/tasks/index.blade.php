@extends('layouts.app')

@section('title', 'Tasks List')

@section('content')

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card stat-card border-0 bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 mr-3 text-primary">
                    <i class="fas fa-clipboard-list fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem;">Total Tasks</span>
                    <h3 class="mb-0 font-weight-bold">{{ $totalCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3 mb-md-0">
        <div class="card stat-card border-0 bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 mr-3 text-warning">
                    <i class="fas fa-hourglass-half fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem;">Pending</span>
                    <h3 class="mb-0 font-weight-bold text-warning">{{ $pendingCount }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card border-0 bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-light p-3 mr-3 text-success">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <span class="text-muted text-uppercase font-weight-bold" style="font-size: 0.75rem;">Completed</span>
                    <h3 class="mb-0 font-weight-bold text-success">{{ $completedCount }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bonus 1: Search & Filter Toolbar -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <form action="{{ route('tasks.index') }}" method="GET" class="form-inline d-flex flex-column flex-md-row justify-content-between">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center w-100 w-md-auto mb-2 ">

                <!-- Search Input -->
                <div class="input-group mr-sm-2 mb-2 mb-sm-0 flex-grow-1" style="min-width: 250px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" placeholder="Search tasks..." value="{{ $search ?? '' }}">
                </div>

                <!-- Status Filter Dropdown -->
                <div class="input-group mr-sm-2 mb-2 mb-sm-0">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0 text-muted"><i class="fas fa-filter"></i></span>
                    </div>
                    <select name="status" class="form-control border-left-0" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ ($status ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ ($status ?? '') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mr-2 mb-2 mb-sm-0">Filter</button>

                @if(!empty($search) || !empty($status))
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary mb-2 mb-sm-0">Clear</a>
                @endif
            </div>

            <a href="{{ route('tasks.create') }}" class="btn btn-danger font-weight-bold w-100 w-md-auto mt-2 mt-md-0">
                <i class="fas fa-plus mr-1"></i> Create Task
            </a>
        </form>
    </div>
</div>

<!-- Live Alert Notification for Drag-and-Drop Reorder -->
<div id="reorderAlert" class="alert alert-info alert-dismissible fade d-none shadow-sm mb-3" role="alert">
    <i class="fas fa-arrows-alt mr-2"></i>
    <span id="reorderAlertText">Task order updated!</span>
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>

<!-- Task List -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-tasks mr-2 text-primary"></i> Tasks</h5>
            <small class="text-muted">
                Showing {{ $tasks->firstItem() ?? 0 }} to {{ $tasks->lastItem() ?? 0 }} of {{ $tasks->total() }} tasks
                @if($tasks->total() > 1 && empty($search) && empty($status))
                &bull; <i class="fas fa-info-circle text-info"></i> Drag grip handle to reorder
                @endif
            </small>
        </div>
    </div>

    <div class="card-body p-0">
        @if($tasks->count() > 0)
        <div id="taskList" class="list-group list-group-flush">
            @foreach ($tasks as $task)
            <div class="list-group-item p-3 task-card {{ $task->isCompleted() ? 'task-completed' : 'task-pending' }} d-flex align-items-center" data-id="{{ $task->id }}">

                <!-- Drag Handle -->
                <div class="drag-handle mr-3" title="Drag to reorder" data-toggle="tooltip">
                    <i class="fas fa-grip-vertical fa-lg"></i>
                </div>

                <!-- Quick Status Toggle -->
                <div class="mr-3">
                    <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="btn btn-status-toggle {{ $task->isCompleted() ? 'btn-success' : 'btn-outline-secondary' }}"
                            title="{{ $task->isCompleted() ? 'Mark as Pending' : 'Mark as Completed' }}"
                            data-toggle="tooltip">
                            <i class="fas {{ $task->isCompleted() ? 'fa-check' : 'fa-circle' }}"></i>
                        </button>
                    </form>
                </div>

                <!-- Task Details -->
                <div class="flex-grow-1 mr-3" style="min-width: 0;">
                    <div class="d-flex flex-wrap align-items-center mb-1">
                        <a href="{{ route('tasks.show', $task) }}" class="task-title font-weight-bold text-dark text-truncate mr-2 h6 mb-0">
                            {{ $task->title }}
                        </a>

                        @if($task->isCompleted())
                        <span class="badge badge-success badge-status"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                        @else
                        <span class="badge badge-warning badge-status text-dark"><i class="fas fa-clock mr-1"></i> Pending</span>
                        @endif
                    </div>

                    @if($task->description)
                    <p class="text-muted small mb-1 text-truncate" style="max-width: 600px;">
                        {{ Str::limit($task->description, 100) }}
                    </p>
                    @endif

                    <div class="text-muted small">
                        <i class="far fa-calendar-alt mr-1"></i>
                        Created on: <strong>{{ $task->created_at->format('M d, Y h:i A') }}</strong>
                        <span class="mx-1">&bull;</span>
                        <span>{{ $task->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex align-items-center">
                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info btn-sm mr-2" title="View Details" data-toggle="tooltip">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary btn-sm mr-2" title="Edit Task" data-toggle="tooltip">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <!-- Delete Trigger Button (Opens Confirmation Modal) -->
                    <button type="button"
                        class="btn btn-outline-danger btn-sm btn-delete-task"
                        data-title="{{ $task->title }}"
                        data-action="{{ route('tasks.destroy', $task) }}"
                        title="Delete Task"
                        data-toggle="tooltip">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-5 px-3">
            <i class="fas fa-tasks fa-4x text-black-50 mb-3"></i>
            <h5 class="font-weight-bold text-dark">No tasks found</h5>
            <p class="text-muted mb-4">No tasks match your current view.</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-danger font-weight-bold px-4">
                <i class="fas fa-plus mr-1"></i> Create First Task
            </a>
        </div>
        @endif
    </div>

    <!-- Bonus 2: Bootstrap 4 Pagination -->
    @if($tasks->hasPages())
    <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap pt-3 pb-2">
        <div class="text-muted small mb-2 mb-md-0">
            Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}
        </div>
        <div>
            {{ $tasks->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Deletion Confirmation Modal -->
<div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Confirm Task Deletion
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-2">Are you sure you want to delete this task?</p>
                <div class="alert alert-light border font-weight-bold text-dark" id="modalTaskTitle"></div>
                <p class="text-danger small mb-0"><i class="fas fa-info-circle mr-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Cancel</button>
                <form id="deleteTaskForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger font-weight-bold px-3">
                        <i class="fas fa-trash-alt mr-1"></i> Yes, Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete Confirmation Modal Handler
        $('.btn-delete-task').on('click', function() {
            var taskTitle = $(this).data('title');
            var formAction = $(this).data('action');

            $('#modalTaskTitle').text(taskTitle);
            $('#deleteTaskForm').attr('action', formAction);
            $('#deleteTaskModal').modal('show');
        });

        // Bonus 3: Drag & Drop with SortableJS
        var taskListElement = document.getElementById('taskList');
        if (taskListElement) {
            new Sortable(taskListElement, {
                handle: '.drag-handle',
                animation: 200,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    var taskIds = [];
                    $('#taskList .list-group-item').each(function() {
                        taskIds.push($(this).data('id'));
                    });

                    $.ajax({
                        url: '{{ route("tasks.reorder") }}',
                        type: 'POST',
                        data: {
                            task_ids: taskIds
                        },
                        success: function(response) {
                            $('#reorderAlertText').text(response.message || 'Task order updated!');
                            $('#reorderAlert').removeClass('d-none').addClass('show alert-success');
                            setTimeout(function() {
                                $('#reorderAlert').removeClass('show').addClass('d-none');
                            }, 3000);
                        }
                    });
                }
            });
        }
    });
</script>
@endpush
