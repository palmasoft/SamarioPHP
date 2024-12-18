<?php
declare(strict_types=1);
use Phinx\Seed\AbstractSeed;

class DatosInicialesSeeder extends AbstractSeed {
  public function run(): void { // Tipo de retorno void añadido
    $roles = [
        ['id' => 1, 'nombre' => 'Administrador', 'descripcion' => 'Control total del sistema.', 'fecha_creacion' => date('Y-m-d H:i:s')],
        ['id' => 2, 'nombre' => 'Usuario', 'descripcion' => 'Usuario estándar del sistema.', 'fecha_creacion' => date('Y-m-d H:i:s')],
    ];

    $permisos = [
        ['id' => 1, 'nombre' => 'gestionar_usuarios', 'descripcion' => 'Administrar los usuarios del sistema.', 'fecha_creacion' => date('Y-m-d H:i:s')],
        ['id' => 2, 'nombre' => 'editar_roles', 'descripcion' => 'Editar los roles del sistema.', 'fecha_creacion' => date('Y-m-d H:i:s')],
        ['id' => 3, 'nombre' => 'ver_dashboard', 'descripcion' => 'Acceder al panel principal.', 'fecha_creacion' => date('Y-m-d H:i:s')],
    ];

    $usuarios = [
        [
            'id' => 1,
            'nombre' => 'Administrador',
            'correo' => 'admin@dominio.com',
            'contrasena' => password_hash('admin123', PASSWORD_DEFAULT),
            'verificado' => 1,
            'fecha_creacion' => date('Y-m-d H:i:s')
        ],
    ];

    $this->table('roles')->insert($roles)->saveData();
    $this->table('permisos')->insert($permisos)->saveData();
    $this->table('usuarios')->insert($usuarios)->saveData();

    // Asociar permisos al rol Administrador (ID = 1)
    $rolAdminId = 1; // ID conocido del rol Administrador
    $permisosIds = [1, 2, 3]; // IDs conocidos de los permisos
    foreach ($permisosIds as $permisoId) {
      $this->table('rol_permiso')->insert([
          'rol_id' => $rolAdminId,
          'permiso_id' => $permisoId
      ])->saveData();
    }

    // Asociar rol Administrador al usuario (ID = 1)
    $usuarioAdminId = 1; // ID conocido del usuario Administrador
    $this->table('usuario_rol')->insert([
        'usuario_id' => $usuarioAdminId,
        'rol_id' => $rolAdminId
    ])->saveData();
  }
}
