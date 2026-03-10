
@extends('layouts.app')

@section('judul', 'Data Pasien')
@section('header', 'Data Pasien')
@section('sub-header', 'Kelola data pasien rawat inap')

@section('konten')
    @livewire('pasien-component')
@endsection