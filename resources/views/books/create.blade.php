@extends('adminlte::page')

@section('title', $title)

@section('content_header')
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0">{{ $title }}</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('genres.index') }}">Genre</a></li>
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('plugins.Select2', true)

@section('content')
<x-adminlte-card theme="teal" theme-mode="outline">
  <form action="{{ route('books.store') }}" method="POST">
    @csrf
    <x-adminlte-input name="judul_buku" label="Book Title" type="text" id="judul_buku" placeholder="Book Title" enable-old-support/>
    <x-adminlte-select2 name="genre_id" label="Genre" enable-old-support>
      <x-adminlte-options empty-option="Select Genre" :options="$genres"/>
    </x-adminlte-select2>
    <x-adminlte-input name="penulis" label="Author" type="text" id="penulis" placeholder="Author" enable-old-support/>
    <x-adminlte-input name="penerbit" label="Publisher" type="text" id="penerbit" placeholder="Publisher" enable-old-support/>
    <x-adminlte-input name="tahun_terbit" label="Publication Year" type="number" id="tahun_terbit" placeholder="Publication Year" enable-old-support/>
    <x-adminlte-textarea name="sinopsis" placeholder="Synopsis" label="Sinopsis" rows=3 enable-old-support/>
    <x-adminlte-button type="submit" label="Submit" theme="primary" class="d-flex ml-auto" name="submit"/>
  </form>
</x-adminlte-card>
@endsection