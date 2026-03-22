
@extends('layouts.app')

@section('judul', 'Data Pengguna')
@section('header', 'Data Pengguna')
@section('sub-header', 'Kelola data pengguna')

@section('konten')
    @livewire('users-component')
@endsection