@extends('layouts.app')


@section('content')
    <div class="pagetitle">
        <h1>Accesories</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
                        <h5 class="card-title">Create</h5>
                        <form action="{{ route('menu.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="" name="title">
                                <span class='text-danger'>
                                    @error('title')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="icon_class" class="form-label">Icon Class</label>
                                <input type="text" name="icon_class" class="form-control" id="">
                                <span class='text-danger'>
                                    @error('icon_class')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Order</label>
                                <input type="number" name="order" class="form-control" id="">
                                <span class='text-danger'>
                                    @error('order')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <label for="inputEmail4" class="form-label">Route</label>
                                <input type="text" name="route" class="form-control" id="">
                                <span class='text-danger'>
                                    @error('route')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="parent_id">Parent Menu:</label>
                                    <select class="form-control" name="parent_id" id="parent_id">
                                        <option value="">No Parent</option>
                                        @foreach($parentMenus as $parentMenu)
                                            <option value="{{ $parentMenu->id }}">{{ $parentMenu->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </form>
                        <!-- Form fields for left form -->

                    </div>
                </div>
            </div>
    </section>
@endsection
