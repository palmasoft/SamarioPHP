<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder {
  public function run() {
    // Crear permisos
    $crearRifaPermission = Permission::create(['name' => 'crear-rifa']);
    $comprarNumeroPermission = Permission::create(['name' => 'comprar-numero']);
    $crearOrganizadorPermission = Permission::create(['name' => 'crear-organizador']);
    $gestionarRifaPermission = Permission::create(['name' => 'gestionar-rifa']);
    $verDashboardPermission = Permission::create(['name' => 'ver-dashboard']);

    // Crear roles
    $superadminRole = Role::create(['name' => 'superadministrador']);
    $organizadorRole = Role::create(['name' => 'organizador']);
    $clienteRole = Role::create(['name' => 'cliente']);
    $impulsadorRole = Role::create(['name' => 'impulsador']);

    // Asignar permisos a roles
    // Superadministrador tiene todos los permisos
    $superadminRole->givePermissionTo([
        $crearRifaPermission,
        $comprarNumeroPermission,
        $crearOrganizadorPermission,
        $gestionarRifaPermission,
        $verDashboardPermission,
    ]);

    // Organizador puede crear y gestionar rifas
    $organizadorRole->givePermissionTo([
        $crearRifaPermission,
        $gestionarRifaPermission,
        $verDashboardPermission,
    ]);

    // Cliente puede comprar números
    $clienteRole->givePermissionTo([
        $comprarNumeroPermission,
        $verDashboardPermission,
    ]);

    // Impulsador puede crear organizadores
    $impulsadorRole->givePermissionTo([
        $crearOrganizadorPermission,
        $verDashboardPermission,
    ]);

    // Crear un superadministrador y asignarle el rol
    $superadmin = User::create([
            'name' => 'Administrador General',
            'email' => 'admin@cogeunnumero.com',
            'password' => bcrypt('adminpassword')
    ]);
    $superadmin->assignRole($superadminRole);

    // Crear un organizador y asignarle el rol
    $organizador = User::create([
            'name' => 'Organizador 1',
            'email' => 'organizador@cogeunnumero.com',
            'password' => bcrypt('organizadorpassword')
    ]);
    $organizador->assignRole($organizadorRole);

    // Crear un cliente y asignarle el rol
    $cliente = User::create([
            'name' => 'Cliente 1',
            'email' => 'cliente@cogeunnumero.com',
            'password' => bcrypt('clientepassword')
    ]);
    $cliente->assignRole($clienteRole);

    // Crear un impulsador y asignarle el rol
    $impulsador = User::create([
            'name' => 'Impulsador 1',
            'email' => 'impulsador@cogeunnumero.com',
            'password' => bcrypt('impulsadorpassword')
    ]);
    $impulsador->assignRole($impulsadorRole);
  }
}