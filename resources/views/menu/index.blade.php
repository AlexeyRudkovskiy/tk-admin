@extends('layout.dashboard')
@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Menu</li>
@endsection

@section('content')
    Menu
@endsection