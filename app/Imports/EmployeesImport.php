<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Role;
use App\Models\StatusHire;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use Importable;

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, $value);
        }
    }

    /**
     * @param \Throwable $e
     */
    public function onError(\Throwable $e)
    {
        if ($e->errorInfo) {
            return $e->errorInfo[2];
        }
    }


    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row['firstname'] == null) {
            return null;
        }
        return new Employee([
            "firstName" => $row["firstname"],
            "lastName" => $row["lastname"],
            "phone" => $row["phone"],
            "email" => $row["email"],
            "password" => bcrypt($row["password"]),
            "photo" => $row["photo"],
            "gender" => $row["gender"],
            "birthDate" => $this->transformDate($row["birthdate"]),
            "address" => $row["address"],
            "city" => $row["city"],
            "nation" => $row["nation"],
            "roleId" => Role::getIdsByName($row["role"]),
            "isActive" => $row["isactive"],
            "joinedAt" => $this->transformDate($row["joinedat"]),
            "resignedAt" => $row["resignedat"],
            "statusHireId" => StatusHire::getIdsByName($row["statushire"]),
        ]);
    }
}
