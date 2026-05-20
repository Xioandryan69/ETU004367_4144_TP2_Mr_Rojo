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
        $employeId = $session->get('employe_id');

        if (empty($employeId)) {
            return redirect()->to('/login');
        }

        if (!empty($arguments)) {
            $role = strtolower((string) $session->get('role'));
            $allowed = array_map('strtolower', $arguments);
            if (!in_array($role, $allowed, true)) {
                $session->setFlashdata('error', 'Acces refuse.');
                return redirect()->to('/');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
