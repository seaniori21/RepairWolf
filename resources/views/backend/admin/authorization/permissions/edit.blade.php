@extends('backend.layouts.app', ['bread' => 'edit', 'submenu' => 'permissions'])

@section('content')

<!-- body header start -->
<header class="body-header">
    <div class="container">
        <div class="header-title">
            <h3>Edit Perimission Item</h3>
        </div>
    </div>
</header>
<!-- body header end -->


<!-- body content start -->
<div class="body-content">
    <div class="container">
        <!-- form content -->
        <form action="{{ route('adminPermissionEditSave', ['data' => $data->id]) }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-sm-8 required mb-4">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" value="{{ $data->name }}" placeholder="Permission Name" class="form-control">
                </div>

                <div class="form-group col-sm-4 required mb-4">
                    <label for="group">Group</label>
                    <input type="text" name="group" id="group" value="{{ $data->group_name }}" placeholder="Permission Group" class="form-control">
                </div>

                <!-- button -->
                <div class="form-group col-12 mb-5">
                    <button type="submit" class="btn btn-success mt-0">Save</button>
                    <button type="reset" class="btn btn-secondary mt-0">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- body content end -->

@endsection

@push('scripts')
@endpush