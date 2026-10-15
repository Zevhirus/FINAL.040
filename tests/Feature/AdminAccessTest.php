<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/proyek')->assertRedirect('/admin/login');
        $this->get('/admin/foto')->assertRedirect('/admin/login');
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->post('/admin/login', ['password' => 'salah'])
            ->assertSessionHasErrors('password');

        $this->get('/admin/proyek')->assertRedirect('/admin/login');
    }

    public function test_correct_password_grants_access(): void
    {
        $this->post('/admin/login', ['password' => 'secret-test'])
            ->assertRedirect(route('admin.dashboard'));

        $this->get('/admin/proyek')->assertOk();
        $this->get('/admin/foto')->assertOk();
    }

    public function test_logout_revokes_access(): void
    {
        $this->post('/admin/login', ['password' => 'secret-test']);
        $this->post('/admin/logout');

        $this->get('/admin/proyek')->assertRedirect('/admin/login');
    }
}
