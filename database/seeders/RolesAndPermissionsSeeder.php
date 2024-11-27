<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run() {
    // Crear permisos
    $createRifaPermission = Permission::create(['name' => 'create-rifa']);
    $buyNumberPermission = Permission::create(['name' => 'buy-number']);
    $createOrganizerPermission = Permission::create(['name' => 'create-organizer']);
    $manageRifaPermission = Permission::create(['name' => 'manage-rifa']);
    $viewDashboardPermission = Permission::create(['name' => 'view-dashboard']);

    // Crear roles
    $superadminRole = Role::create(['name' => 'superadmin']);
    $organizerRole = Role::create(['name' => 'organizer']);
    $clientRole = Role::create(['name' => 'client']);
    $impulsadorRole = Role::create(['name' => 'impulsador']);

    // Asignar permisos a roles
    // Superadmin tiene todos los permisos
    $superadminRole->givePermissionTo([
        $createRifaPermission,
        $buyNumberPermission,
        $createOrganizerPermission,
        $manageRifaPermission,
        $viewDashboardPermission,
    ]);

    // Organizador puede crear y gestionar rifas
    $organizerRole->givePermissionTo([
        $createRifaPermission,
        $manageRifaPermission,
        $viewDashboardPermission,
    ]);

    // Cliente puede comprar números
    $clientRole->givePermissionTo([
        $buyNumberPermission,
        $viewDashboardPermission,
    ]);

    // Impulsador puede crear organizadores
    $impulsadorRole->givePermissionTo([
        $createOrganizerPermission,
        $viewDashboardPermission,
    ]);

    // Crear un superadmin y asignarle el rol
    $superadmin = User::create([
            'name' => 'Admin Super',
            'email' => 'admin@cogeunnumero.com',
            'password' => bcrypt('adminpassword')
    ]);
    $superadmin->assignRole($superadminRole);

    // Crear un organizador y asignarle el rol
    $organizer = User::create([
            'name' => 'Organizador 1',
            'email' => 'organizer@cogeunnumero.com',
            'password' => bcrypt('organizerpassword')
    ]);
    $organizer->assignRole($organizerRole);

    // Crear un cliente y asignarle el rol
    $client = User::create([
            'name' => 'Cliente 1',
            'email' => 'client@cogeunnumero.com',
            'password' => bcrypt('clientpassword')
    ]);
    $client->assignRole($clientRole);

    // Crear un impulsador y asignarle el rol
    $impulsador = User::create([
            'name' => 'Impulsador 1',
            'email' => 'impulsador@cogeunnumero.com',
            'password' => bcrypt('impulsadorpassword')
    ]);
    $impulsador->assignRole($impulsadorRole);
  }
}