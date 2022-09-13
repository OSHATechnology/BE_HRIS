@extends('employee.master')

@section('content')
<div class="container">
  <a href="/employee/add" class="btn btn-primary">Tambah</a>
  <button class="btn btn-primary">Import</button>
  <button class="btn btn-primary">Export</button>
  <table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($employees as $employee)
    <tr>
      <td>{{ $employee->firstName}} {{ $employee->lastName }}</td>   
      <td>
        <a href="/employee/edit" type="submit" class="btn btn-info">Detail</a>
        <button type="button" class="btn btn-warning">Edit</button>
        <button type="button" class="btn btn-danger">Hapus</button>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
    
@endsection