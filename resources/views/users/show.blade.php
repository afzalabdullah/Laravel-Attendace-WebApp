@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Users</a></li>
                <li class="breadcrumb-item">Forms</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <style>
            .details-container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .details-row {
                margin-bottom: 10px;
                display: flex;
                justify-content: space-between;
            }

            .details-label {
                font-weight: bold;
                width: 40%;
            }

            .details-value {
                width: 60%;
            }
        </style>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users Details</h5>
                        <div class="col-12">
                       
                        <div class="details-container">
                            <div class="details-row">
                                <span class="details-label">Name:</span>
                                <span class="details-value">{{ $user->name }}</span>
                            </div>
                            <div class="details-row">
                                <span class="details-label">Email:</span>
                                <span class="details-value">{{ $user->email }}</span>
                            </div>

                            @if(Auth::user()->isAdmin() || Auth::user()->id === $user->id)
                                <div class="details-row">
                                    <span class="details-label">Department:</span>
                                    <span class="details-value">{{ $user->department }}</span>
                                </div>
                            @endif

                            
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection