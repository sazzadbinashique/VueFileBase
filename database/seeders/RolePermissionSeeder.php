<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',
            'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
            'donations.view', 'donations.edit',
            'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
            'videos.view', 'videos.create', 'videos.edit', 'videos.delete',
            'cms.view', 'cms.create', 'cms.edit', 'cms.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
        ];

        $permissionLabels = [
            'dashboard.view' => 'View Dashboard',
            'projects.view' => 'View Projects', 'projects.create' => 'Create Projects',
            'projects.edit' => 'Edit Projects', 'projects.delete' => 'Delete Projects',
            'donations.view' => 'View Donations', 'donations.edit' => 'Edit Donations',
            'gallery.view' => 'View Gallery', 'gallery.create' => 'Create Gallery Items',
            'gallery.edit' => 'Edit Gallery Items', 'gallery.delete' => 'Delete Gallery Items',
            'videos.view' => 'View Videos', 'videos.create' => 'Create Videos',
            'videos.edit' => 'Edit Videos', 'videos.delete' => 'Delete Videos',
            'cms.view' => 'View CMS Pages', 'cms.create' => 'Create CMS Pages',
            'cms.edit' => 'Edit CMS Pages', 'cms.delete' => 'Delete CMS Pages',
            'users.view' => 'View Users', 'users.create' => 'Create Users',
            'users.edit' => 'Edit Users', 'users.delete' => 'Delete Users',
            'roles.view' => 'View Roles', 'roles.create' => 'Create Roles',
            'roles.edit' => 'Edit Roles', 'roles.delete' => 'Delete Roles',
        ];

        $created = [];
        foreach ($permissions as $perm) {
            $created[$perm] = Permission::firstOrCreate(
                ['name' => $perm],
                ['label' => $permissionLabels[$perm] ?? $perm]
            );
        }

        $roles = [
            'admin' => ['label' => 'Administrator', 'permissions' => $permissions],
            'manager' => ['label' => 'Manager', 'permissions' => [
                'dashboard.view',
                'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
                'donations.view', 'donations.edit',
                'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
                'videos.view', 'videos.create', 'videos.edit', 'videos.delete',
                'cms.view', 'cms.create', 'cms.edit', 'cms.delete',
                'users.view',
            ]],
            'editor' => ['label' => 'Editor', 'permissions' => [
                'dashboard.view',
                'projects.view', 'projects.edit',
                'gallery.view', 'gallery.create', 'gallery.edit', 'gallery.delete',
                'videos.view', 'videos.create', 'videos.edit', 'videos.delete',
                'cms.view', 'cms.create', 'cms.edit',
            ]],
            'user' => ['label' => 'User', 'permissions' => []],
        ];

        foreach ($roles as $name => $cfg) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                ['label' => $cfg['label']]
            );
            $role->permissions()->sync(
                collect($cfg['permissions'])->map(fn($p) => $created[$p]->id)
            );
        }
    }
}
