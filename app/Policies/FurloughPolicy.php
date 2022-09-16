<?php

namespace App\Policies;

use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class FurloughPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(Employee $employee)
    {
        return $employee->hasPermissionTo('can_view_all_furloughs');
    }

    /**
     * Determine whether the user can view models.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Employee $employee)
    {
        return $employee->hasPermissionTo('can_view_furlough');
    }

    /**
     * Determine whether the user can create furloughs.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Employee $employee)
    {
        return $employee->hasPermissionTo('can_create_furlough');
    }

    /**
     * Determine whether the user can update furlough.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Employee $employee)
    {
        return $employee->hasPermissionTo('can_update_furlough');
    }

    /**
     * Determine whether the user can delete furlough.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Employee $employee)
    {
        return $employee->hasPermissionTo('can_delete_furlough');
    }
}
