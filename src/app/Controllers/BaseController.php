<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class BaseController extends Controller
{
    /**
     * @var array<int, string>
     */
    protected $helpers = ['form', 'url'];

    protected function requireRole(string $role)
    {
        $session = session();
        if ($session->get('role') !== $role) {
            $session->setFlashdata('error', 'Acces refuse.');
            return redirect()->to('/');
        }

        return null;
    }

    /**
     * @param array<int, string> $roles
     */
    protected function requireAnyRole(array $roles)
    {
        $session = session();
        $current = (string) $session->get('role');
        if (!in_array($current, $roles, true)) {
            $session->setFlashdata('error', 'Acces refuse.');
            return redirect()->to('/');
        }

        return null;
    }
}
