<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Pas connecté → login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // 2. Vérifier le rôle si des arguments sont fournis
        if (!empty($arguments)) {
            $role = (string) $session->get('role');

            // CI4 passe 'rh,admin' comme une string dans le tableau
            // On explose par virgule pour avoir un vrai tableau de rôles
            $allowedRoles = [];
            foreach ($arguments as $arg) {
                foreach (explode(',', $arg) as $r) {
                    $allowedRoles[] = trim($r);
                }
            }

            if (!in_array($role, $allowedRoles, true)) {
                // Rôle non autorisé → son propre espace
                switch ($role) {
                    case 'admin': return redirect()->to(site_url('admin'));
                    case 'rh':    return redirect()->to(site_url('rh'));
                    default:      return redirect()->to(site_url('employe/dashboard'));
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
