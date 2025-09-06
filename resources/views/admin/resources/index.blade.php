@extends('layouts.app')

@section('title', 'Learning Resource Management')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Heading -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>Learning Resource Management</h2>
                <div>
                    <button type="button" class="btn btn-primary fw-bold me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-folder-plus me-1"></i> New Category
                    </button>
                    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                        <i class="fas fa-plus-circle me-1"></i> New Resource
                    </button>
                </div>
            </div>
            <p class="text-muted mb-0">Organize and distribute educational resources to teachers</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-file-alt text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Resources</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalResources ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-tags text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Resource Categories</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalCategories ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-white border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-award text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Most Accessed</h6>
                            <h3 class="mb-0 fw-bold text-primary">{{ $mostUsedCount ?? 0 }}</h3>
                            <p class="text-muted small mb-0">{{ Str::limit($mostUsedTitle ?? 'None', 28) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card border-0 bg-white shadow-sm mb-4">
        <div class="card-header bg-light py-3 border-0">
            <ul class="nav nav-tabs card-header-tabs" id="resourceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="resources-tab" data-bs-toggle="tab" data-bs-target="#resources" type="button" role="tab" aria-controls="resources" aria-selected="true">
                        <i class="fas fa-link me-1"></i> Resource Links
                        <span class="badge bg-primary rounded-pill ms-1">{{ $totalResources ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                        <i class="fas fa-tags me-1"></i> Categories
                        <span class="badge bg-success rounded-pill ms-1">{{ $totalCategories ?? 0 }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="resourceTabsContent">
                <!-- Resources Tab -->
                <div class="tab-pane fade show active" id="resources" role="tabpanel" aria-labelledby="resources-tab">
                    <!-- Search and Filter Section -->
                    <div class="card bg-white border-0">
                        <div class="card-body">
                            <form action="{{ route('admin.resources.index') }}" method="GET" class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0" 
                                               placeholder="Search resources..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <select name="quarter" class="form-select">
                                        <option value="">All Quarters</option>
                                        <option value="1" {{ request('quarter') == '1' ? 'selected' : '' }}>1st Quarter</option>
                                        <option value="2" {{ request('quarter') == '2' ? 'selected' : '' }}>2nd Quarter</option>
                                        <option value="3" {{ request('quarter') == '3' ? 'selected' : '' }}>3rd Quarter</option>
                                        <option value="4" {{ request('quarter') == '4' ? 'selected' : '' }}>4th Quarter</option>
                                        <option value="null" {{ request('quarter') == 'null' ? 'selected' : '' }}>Unassigned</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @if(isset($categories))
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="sort" class="form-select">
                                        <option value="">Sort by...</option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                                        <option value="click_count" {{ request('sort') == 'click_count' ? 'selected' : '' }}>Popularity</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="order" class="form-select">
                                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Asc</option>
                                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Desc</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i> Apply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resources Table -->
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="background-color: white;">
                                    <thead class="table-light" style="background-color: #f8f9fa;">
                                        <tr>
                                            <th scope="col">Resource</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Quarter</th>
                                            <th scope="col">URL</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Analytics</th>
                                            <th scope="col" class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color: white;">
                                        @php
                                            $filteredResources = $resources;

                                            if (request('search')) {
                                                $searchTerm = strtolower(request('search'));
                                                $filteredResources = $resources->filter(function($resource) use ($searchTerm) {
                                                    return str_contains(strtolower($resource->title), $searchTerm) ||
                                                           str_contains(strtolower($resource->description), $searchTerm) ||
                                                           ($resource->category && str_contains(strtolower($resource->category->name), $searchTerm));
                                                });
                                            }

                                            if (request('quarter') && request('quarter') !== 'all') {
                                                $quarter = request('quarter') === 'null' ? null : request('quarter');
                                                $filteredResources = $filteredResources->where('quarter', $quarter);
                                            }

                                            if (request('category')) {
                                                $filteredResources = $filteredResources->where('category_id', request('category'));
                                            }

                                            if (request('status') && request('status') !== 'all') {
                                                $filteredResources = $filteredResources->where('is_active', request('status') === 'active');
                                            }

                                            if (request('sort')) {
                                                $sortField = request('sort');
                                                $sortOrder = request('order', 'asc');
                                                $filteredResources = $filteredResources->sortBy(function($resource) use ($sortField) {
                                                    switch ($sortField) {
                                                        case 'title':
                                                            return strtolower($resource->title);
                                                        case 'created_at':
                                                            return $resource->created_at;
                                                        case 'click_count':
                                                            return $resource->click_count;
                                                        default:
                                                            return strtolower($resource->title);
                                                    }
                                                }, SORT_REGULAR, $sortOrder === 'desc');
                                            }
                                        @endphp

                                        @forelse($filteredResources as $resource)
                                            <tr style="background-color: white;">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <div class="bg-{{ $resource->category_color ?? 'primary' }} bg-opacity-10 rounded-circle p-2">
                                                                <i class="fas fa-{{ $resource->icon ?? 'file-alt' }} text-{{ $resource->category_color ?? 'primary' }}"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $resource->title }}</h6>
                                                            <small class="text-muted">{{ Str::limit($resource->description, 60) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $resource->category_color ?? 'light' }} bg-opacity-10 text-{{ $resource->category_color ?? 'dark' }}">
                                                        <i class="fas fa-{{ $resource->icon ?? 'folder' }} me-1"></i> {{ $resource->category_name ?? 'Uncategorized' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($resource->quarter)
                                                        <span class="badge bg-info bg-opacity-10 text-info">
                                                            {{ $resource->quarter_name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            Unassigned
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-truncate" style="max-width: 200px;">
                                                            <a href="{{ $resource->url }}" target="_blank" class="text-decoration-none">
                                                                {{ $resource->url }}
                                                            </a>
                                                        </div>
                                                        <button class="btn btn-sm btn-link p-0 ms-2 copy-link" data-url="{{ $resource->url }}" title="Copy URL">
                                                            <i class="fas fa-copy text-muted"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $resource->is_active ? 'success' : 'secondary' }}">
                                                        {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold me-2">{{ $resource->click_count ?? 0 }}</span>
                                                        <div class="progress flex-grow-1" style="height: 6px;">
                                                            @php
                                                                $percentage = $mostUsedCount > 0 ? min(100, (($resource->click_count ?? 0) / $mostUsedCount) * 100) : 0;
                                                            @endphp
                                                            <div class="progress-bar bg-info" style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-primary"
                                                                data-bs-toggle="modal" data-bs-target="#editResourceModal"
                                                                data-id="{{ $resource->id }}"
                                                                data-title="{{ $resource->title }}"
                                                                data-description="{{ $resource->description }}"
                                                                data-url="{{ $resource->url }}"
                                                                data-category="{{ $resource->category_id }}"
                                                                data-quarter="{{ $resource->quarter }}"
                                                                title="Edit Resource">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger delete-resource-btn"
                                                                data-id="{{ $resource->id }}"
                                                                data-title="{{ $resource->title }}"
                                                                title="Delete Resource">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4" style="background-color: white;">
                                                    <div class="text-muted">
                                                        <i class="fas fa-file-alt fa-2x mb-3"></i>
                                                        <h5>No Resources Found</h5>
                                                        @if(request('search') || request('quarter') || request('category') || request('status') !== 'all')
                                                            <p>No resources match your search or filter criteria.</p>
                                                            <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary me-2">
                                                                <i class="fas fa-times me-1"></i> Clear Filters
                                                            </a>
                                                        @else
                                                            <p>Start by adding educational resources for teachers to access.</p>
                                                        @endif
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResourceModal">
                                                            <i class="fas fa-plus-circle me-1"></i> Add Resource
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($filteredResources->count() > 0)
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $resources->appends(request()->query())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                        </div>

                        <!-- Categories Tab -->
                        <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                            <div class="d-flex justify-content-between align-items-center p-3 mb-4">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 fw-bold">Manage Categories</h5>
                                    <span class="badge bg-primary ms-2">{{ $totalCategories ?? 0 }} Categories</span>
                                </div>
                                <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus-circle me-1"></i> Add New Category
                                </button>
                            </div>

                            @if(isset($categories) && count($categories) > 0)
                                <div class="row g-4">
                                    @foreach($categories as $category)
                                        <div class="col-md-6 col-lg-4 col-xl-3">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-{{ $category->color }} bg-opacity-10 rounded-circle p-3 me-3">
                                                                <i class="fas fa-{{ $category->icon }} text-{{ $category->color }} fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $category->name }}</h6>
                                                                <span class="badge bg-{{ $category->color }} bg-opacity-10 text-{{ $category->color }}">
                                                                    {{ $category->resources_count ?? 0 }} Resources
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input category-status-toggle" type="checkbox" role="switch"
                                                                data-id="{{ $category->id }}" {{ $category->is_active ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <p class="small text-muted mb-2">{{ $category->description ?: 'No description provided.' }}</p>
                                                        <div class="d-flex align-items-center text-muted small">
                                                            <span class="me-3">
                                                                <i class="fas fa-palette me-1"></i> {{ ucfirst($category->color) }}
                                                            </span>
                                                            <span>
                                                                <i class="fas fa-{{ $category->icon }} me-1"></i> {{ $category->icon }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                                        <button type="button" class="btn btn-sm btn-outline-primary edit-category-btn"
                                                            data-id="{{ $category->id }}"
                                                            data-name="{{ $category->name }}"
                                                            data-description="{{ $category->description }}"
                                                            data-icon="{{ $category->icon }}"
                                                            data-color="{{ $category->color }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editCategoryModal">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger delete-category-btn"
                                                            data-id="{{ $category->id }}"
                                                            data-name="{{ $category->name }}"
                                                            data-resource-count="{{ $category->resources_count ?? 0 }}">
                                                            <i class="fas fa-trash-alt me-1"></i> Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-tags fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No Categories Available</h5>
                                    <p class="text-muted mb-4">Categories help organize your learning resources. Create your first category to get started.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                        <i class="fas fa-plus-circle me-1"></i> Create First Category
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Resource Modal -->
<div class="modal fade" id="addResourceModal" tabindex="-1" aria-labelledby="addResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResourceModalLabel">
                    <i class="fas fa-plus-circle me-2"></i> Add New Resource
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addResourceForm" action="{{ route('admin.resources.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="url" name="url" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quarter" class="form-label">Quarter <span class="text-danger">*</span></label>
                            <select class="form-select" id="quarter" name="quarter" required>
                                <option value="">Select Quarter</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Resource Modal -->
<div class="modal fade" id="editResourceModal" tabindex="-1" aria-labelledby="editResourceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResourceModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Resource
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editResourceForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_quarter" class="form-label">Quarter <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_quarter" name="quarter" required>
                                <option value="">Select Quarter</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_url" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="edit_url" name="url" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">
                    <i class="fas fa-folder-plus me-2"></i> Add New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm" action="{{ route('admin.resource-categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="icon" class="form-label">Icon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">fas fa-</span>
                                <input type="text" class="form-control" id="icon" name="icon" required>
                            </div>
                            <div class="form-text small">FontAwesome icon name</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="color" name="color" required>
                                <option value="primary">Primary</option>
                                <option value="secondary">Secondary</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">
                    <i class="fas fa-edit me-2"></i> Edit Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_icon" class="form-label">Icon <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">fas fa-</span>
                                <input type="text" class="form-control" id="edit_icon" name="icon" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_color" class="form-label">Color <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_color" name="color" required>
                                <option value="primary">Primary</option>
                                <option value="secondary">Secondary</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Card hover effect */
    .card-hover:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    
    /* Table styles */
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #6c757d;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    /* Progress bar */
    .progress {
        height: 8px;
        border-radius: 4px;
        background-color: #e9ecef;
    }
    
    /* Badge styles */
    .badge {
        font-weight: 500;
        padding: 0.4em 0.6em;
    }
    
    /* Button group styles */
    .btn-group-sm > .btn, .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    /* Tabs */
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
    }
    
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background: transparent;
    }
    
    /* Form controls */
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Modal header */
    .modal-header {
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
    
    /* Modal footer */
    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Copy URL functionality
        document.querySelectorAll('.copy-link').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                navigator.clipboard.writeText(url).then(() => {
                    // Show feedback
                    const originalTitle = this.getAttribute('title');
                    this.setAttribute('title', 'Copied!');
                    const tooltip = bootstrap.Tooltip.getInstance(this);
                    tooltip.show();
                    
                    // Revert after 2 seconds
                    setTimeout(() => {
                        this.setAttribute('title', originalTitle);
                        tooltip.hide();
                    }, 2000);
                });
            });
        });

        // Edit Resource Modal
        const editResourceModal = document.getElementById('editResourceModal');
        if (editResourceModal) {
            editResourceModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const title = button.getAttribute('data-title');
                const description = button.getAttribute('data-description');
                const url = button.getAttribute('data-url');
                const category = button.getAttribute('data-category');
                const quarter = button.getAttribute('data-quarter');
                
                const modal = this;
                modal.querySelector('#edit_title').value = title;
                modal.querySelector('#edit_description').value = description || '';
                modal.querySelector('#edit_url').value = url;
                modal.querySelector('#edit_category_id').value = category || '';
                modal.querySelector('#edit_quarter').value = quarter || '';
                modal.querySelector('form').action = `/admin/resources/${id}`;
            });
        }

        // Delete Resource
        document.querySelectorAll('.delete-resource-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                
                if (confirm(`Are you sure you want to delete the resource "${title}"?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/resources/${id}`;
                    form.style.display = 'none';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Edit Category Modal
        const editCategoryModal = document.getElementById('editCategoryModal');
        if (editCategoryModal) {
            editCategoryModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');
                const description = button.getAttribute('data-description');
                const icon = button.getAttribute('data-icon');
                const color = button.getAttribute('data-color');
                
                const modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_description').value = description || '';
                modal.querySelector('#edit_icon').value = icon || '';
                modal.querySelector('#edit_color').value = color || 'primary';
                modal.querySelector('form').action = `/admin/resource-categories/${id}`;
            });
        }

        // Delete Category
        document.querySelectorAll('.delete-category-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const resourceCount = parseInt(this.getAttribute('data-resource-count') || 0);
                
                let message = `Are you sure you want to delete the category "${name}"?`;
                if (resourceCount > 0) {
                    message += ` This category contains ${resourceCount} resources which will be moved to uncategorized.`;
                }
                
                if (confirm(message)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/resource-categories/${id}`;
                    form.style.display = 'none';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    
                    form.appendChild(methodInput);
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // Toggle Resource Status
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isActive = this.checked;
                const label = this.nextElementSibling;
                
                // Update UI immediately for better UX
                if (label) {
                    label.textContent = isActive ? 'Active' : 'Inactive';
                    label.className = `form-check-label ${isActive ? 'text-success' : 'text-secondary'}`;
                }
                
                // Send request to update status
                fetch(`/admin/resources/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error updating resource status:', error);
                    // Revert UI on error
                    this.checked = !isActive;
                    if (label) {
                        label.textContent = !isActive ? 'Active' : 'Inactive';
                        label.className = `form-check-label ${!isActive ? 'text-success' : 'text-secondary'}`;
                    }
                    alert('Failed to update resource status. Please try again.');
                });
            });
        });

        // Toggle Category Status
        document.querySelectorAll('.category-status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                const isActive = this.checked;
                
                // Send request to update status
                fetch(`/admin/resource-categories/${id}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ is_active: isActive })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Error updating category status:', error);
                    // Revert UI on error
                    this.checked = !isActive;
                    alert('Failed to update category status. Please try again.');
                });
            });
        });
        
        // Initialize color previews
        function updateColorPreview(selectId, previewId) {
            const select = document.getElementById(selectId);
            const preview = document.getElementById(previewId);
            
            if (select && preview) {
                const updatePreview = () => {
                    const color = select.value;
                    preview.innerHTML = `<span class="badge bg-${color}">${color.charAt(0).toUpperCase() + color.slice(1)}</span>`;
                };
                
                select.addEventListener('change', updatePreview);
                updatePreview(); // Initial update
            }
        }
        
        updateColorPreview('color', 'colorPreview');
        updateColorPreview('edit_color', 'editColorPreview');
    });
</script>
@endpush