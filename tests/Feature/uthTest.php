<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestCase;

class AuthTest extends FeatureTestCase
{
    public function testLoginRouteExists()
    {
        $result = $this->get('login');
        $result->assertStatus(200);
    }

    public function testAuthPostRouteExists()
    {
        $result = $this->post('auth', [
            'correo' => 'test@example.com',
            'clave' => 'password123'
        ]);
        $result->assertStatus(302); // Redirige si la autenticación es exitosa o tiene que redirigir
    }
}
