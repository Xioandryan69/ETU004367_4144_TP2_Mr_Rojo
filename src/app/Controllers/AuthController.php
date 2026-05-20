<?php

namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function login()
    {
        $session = session();
        if (strtolower($this->request->getMethod()) === 'post') {
            $email = trim((string) $this->request->getPost('email'));
            $password = (string) $this->request->getPost('password');

            if ($email === '' || $password === '') {
                $session->setFlashdata('error', 'Email et mot de passe requis.');
                return redirect()->to('/login');
            }

            $model = new EmployeModel();
            $employe = $model->findByEmail($email);

            if (!$employe || !password_verify($password, $employe['password'])) {
                $session->setFlashdata('error', 'Identifiants incorrects.');
                return redirect()->to('/login');
            }

            if ((int) $employe['actif'] !== 1) {
                $session->setFlashdata('error', 'Compte desactive.');
                return redirect()->to('/login');
            }

            $session->regenerate();
            $session->set([
                'employe_id' => $employe['id'],
                'employe_nom' => trim($employe['prenom'] . ' ' . $employe['nom']),
                'role' => $employe['role'],
                'departement_id' => $employe['departement_id'],
            ]);

            switch ($employe['role']) {
                case 'admin':
                    return redirect()->to('/admin');
                case 'rh':
                    return redirect()->to('/rh');
                default:
                    return redirect()->to('/employe');
            }
        }

        return view('auth/login', [
            'title' => 'Connexion',
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
