<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form action="/employee/store" method="POST">
    @csrf
    @method('POST')
    <table>
      <tr>
        <td>First Name</td>
        <td>:</td>
        <td>
          <input type="text" name="firstName">
        </td>
      </tr>
      <tr>
        <td>Last Name</td>
        <td>:</td>
        <td>
          <input type="text" name="lastName">
        </td>
      </tr>
      <tr>
        <td>Phone</td>
        <td>:</td>
        <td>
          <input type="text" name="phone">
        </td>
      </tr>
      <tr>
        <td>email</td>
        <td>:</td>
        <td>
          <input type="email" name="email">
        </td>
      </tr>
      <tr>
        <td>Password</td>
        <td>:</td>
        <td>
          <input type="password" name="password">
        </td>
      </tr>
      <tr>
        <td>Photo</td>
        <td>:</td>
        <td>
          <input type="file" name="photo">
        </td>
      </tr>
      <tr>
        <td>Gender</td>
        <td>:</td>
        <td>
          <input type="radio" id="html" name="gender" value="man">
          <label for="html">Man</label><br>
          <input type="radio" id="css" name="gender" value="woman">
          <label for="css">Woman</label><br>
        </td>
      </tr>
      <tr>
        <td>Birth Date</td>
        <td>:</td>
        <td>
          <input type="date" name="birthDate">
        </td>
      </tr>
      <tr>
        <td>City</td>
        <td>:</td>
        <td>
          <input type="text" name="city">
        </td>
      </tr>
      <tr>
        <td>Nation</td>
        <td>:</td>
        <td>
          <input type="text" name="nation">
        </td>
      </tr>
      <tr>
        <td>role</td>
        <td>:</td>
        <td>
          <select name="roleId" id="role">
            @foreach ($roles as $role)
            <option value="{{ $role->roleId }}">{{ $role->nameRole }}</option>
            @endforeach
          </select>
        </td>
      </tr>
      <tr>
        <td>Active</td>
        <td>:</td>
        <td>
          <select name="roleId" id="role">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Joined</td>
        <td>:</td>
        <td>
          <input type="date" name="joinedAt">
        </td>
      </tr>
      <tr>
        <td>Status Hired</td>
        <td>:</td>
        <td>
          <select name="roleId" id="role">
            @foreach ($statusHires as $statusHire)
            <option value="{{ $statusHire->StatusHireId }}">{{ $statusHire->name }}</option>
            @endforeach
          </select>
        </td>
      </tr>
    </table>
    <button type="submit">Submit</button>
  </form>
</body>
</html>