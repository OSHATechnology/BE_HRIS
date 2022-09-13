<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  Hallo
  <div class="container">
    <button class="btn btn-success">Tambah</button>
    <button class="btn btn-info">Import</button>
    <button class="btn btn-info">Export</button>
    <div class="card">
      <table class="table table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">email</th>
            <th scope="col">photo</th>
            <th scope="col">gender</th>
            <th scope="col">roleId</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          @foreach ($employees as $employee)
            <tr>
              <th scope="row">{{ $i }}</th>
              <td>{{ $employee->firstName }} {{ $employee->lastName }}</td>
              <td>{{ $employee->email }}</td>
              <td>{{ $employee->photo }}</td>
              <td>{{ $employee->gender }}</td>
              <td>{{ $employee->role }}</td>
              <td>
                <button class="btn btn-info">Detail</button>
                <button class="btn btn-warning">Edit</button>
                <button class="btn btn-danger">Delete</button>
              </td>
              <?php $i++ ?>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</body>
</html>