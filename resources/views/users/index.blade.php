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
        <li class="breadcrumb-item active">{{ $title }}</li>
      </ol>
    </div><!-- /.col -->
  </div><!-- /.row -->
</div><!-- /.container-fluid -->
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-title">
      <a href="{{ route('users.create') }}" class="btn btn-primary">Create New User</a>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    @if (isset($config['data']))
    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" :config="$config">
      @foreach($config['data'] as $row)
          <tr>
              @foreach($row as $cell)
                <td>{!! $cell !!}</td>
              @endforeach
          </tr>
      @endforeach
    </x-adminlte-datatable>
    @else
    <dt class="p-3">
      Users Not Found
    </dt>
    @endif
  </div>
  <!-- /.card-body -->
</div>

@foreach ($config['data'] as $row)
<form method="post" action="{{ route('users.destroy', ['user' => $row[0]])}}">
  <x-adminlte-modal id="modalDelete{{ $row[0] }}" title="Delete User" theme="teal"
      icon="fas fa-bolt" size='lg' disable-animations>
      Are you sure you want to delete {{ $row[1] }}?
      @csrf @method('delete')
      <x-slot name="footerSlot">
        <x-adminlte-button type="submit" name="submit" class="mr-auto" theme="success" label="Yes"/>
        <x-adminlte-button theme="danger" label="No" data-dismiss="modal"/>
      </x-slot>
  </x-adminlte-modal>
</form>
@endforeach


@endsection