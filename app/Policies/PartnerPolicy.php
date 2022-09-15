<?php

namespace App\Policies;

use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class PartnerPolicy
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
        return $employee->hasPermissionTo('can_view_all_partners');
    }

    /**
     * Determine whether the user can view models.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Employee $employee)
    {
        return $employee->hasPermissionTo('can_view_partner');
    }

    /**
     * Determine whether the user can create partners.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(Employee $employee)
    {
        return $employee->hasPermissionTo('can_create_partner');
    }

    /**
     * Determine whether the user can update partner.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(Employee $employee)
    {
        return $employee->hasPermissionTo('can_update_partner');
    }

    /**
     * Determine whether the user can delete partner.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(Employee $employee)
    {
        return $employee->hasPermissionTo('can_delete_partner');
    }
}
