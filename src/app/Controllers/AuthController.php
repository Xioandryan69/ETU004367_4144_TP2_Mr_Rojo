<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    private array $dashboards = [
        'admin'   => 'admin',
        'rh'      => 'rh',
        'employe' => 'employe/dashboard',
    ];

    public function home()
    {
        return view('login');
    }
    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model   = new EmployeModel();
        $employe = $model->getEmployeByEmail($email);

        if (!$employe || (int)$employe['actif'] !== 1 ) {
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect, ou compte inactif.');
        }

        session()->set([
            'id'         => $employe['id'],
            'nom'        => $employe['prenom'] . ' ' . $employe['nom'],
            'email'      => $employe['email'],
            'role'       => $employe['role'],
            'isLoggedIn' => true,
        ]);

        $destination = $this->dashboards[$employe['role']] ?? 'employe';

        return redirect()->to(base_url($destination . "/"));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }

    // ─── Redirection selon le rôle ──────────────────────────────────────────
    private function redirectByRole(string $role)
    {
        // Cherche le dashboard dans le tableau $dashboards
        // Si le rôle n'existe pas, on envoie vers employe par défaut
        $destination = $this->dashboards[$role] ?? 'employe/dashboard';
        return redirect()->to(site_url($destination));
    }
}
