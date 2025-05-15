@extends('layouts.app')

@section('content')
<h1>Customer Dashboard</h1>
<p>Selamat datang, {{ Auth::user()->name }}</p>

<!-- Tempatkan fitur yang bisa diakses oleh customer di sini -->
@endsection
