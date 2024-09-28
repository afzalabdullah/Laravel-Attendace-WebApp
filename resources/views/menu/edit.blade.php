@extends('layouts.app')


@section('content')
    <div class="pagetitle">
        <h1>Menus</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Layouts</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Menu</h5>
                        <form action="{{ route('menu.update', ['menu' => $menu->id]) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ $menu->title }}">
                                <span class='text-danger'>
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="icon_class" class="form-label">Icon Class</label>
                                <input type="text" class="form-control" id="icon_class" name="icon_class"
                                    value="{{ $menu->icon_class }}">
                                <span class='text-danger'>
                                    @error('icon_class')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="order" class="form-label">Order</label>
                                <input type="number" name="order" class="form-control" id="order"
                                    value="{{ $menu->order }}">
                                <span class='text-danger'>
                                    @error('order')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="route" class="form-label">Route</label>
                                <input type="text" name="route" class="form-control" id="route"
                                    value="{{ $menu->route }}">
                                <span class='text-danger'>
                                    @error('route')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="parent_id" class="form-label">Parent Menu</label>
                                <select name="parent_id" id="parent_id" class="form-control">
                                    <option value="">No Parent</option>
                                    @foreach ($parentMenus as $parentMenu)
                                        <option value="{{ $parentMenu->id }}" {{ $menu->parent_id == $parentMenu->id ? 'selected' : '' }}>
                                            {{ $parentMenu->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class='text-danger'>
                                    @error('parent_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
@endsection
