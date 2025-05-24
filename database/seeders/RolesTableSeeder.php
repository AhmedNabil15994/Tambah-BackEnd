<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Authorization\Entities\Role;
use Modules\Authorization\Entities\Permission;
use Illuminate\Support\Facades\DB;
use Modules\User\Entities\User;

class RolesTableSeeder extends Seeder
{

    private $roles = [
        'admins' => [
            'title_en' => 'Super Admin',
            'title_ar' => 'مدير لوحة التحكم',
        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        (new PermissionsTableSeeder())->run();

        foreach ($this->roles as $name => $role_data){

            $role = Role::updateOrCreate([
                'name' => $name,
            ],[
                'name' => $name,
                'display_name' => ['en' => $role_data['title_en'],'ar' => $role_data['title_ar']]
            ]);

            if($name == 'admins') {

                DB::table('permission_role')->where('role_id', $role->id)->delete();
                foreach (Permission::all() as $permission) {
                    $role->attachPermission($permission->id);
                }
                $user = User::find(1);

                if($user){
                    $user->roles()->sync([$role->id]);
                }
            }
        }

        DB::commit();
    }
}