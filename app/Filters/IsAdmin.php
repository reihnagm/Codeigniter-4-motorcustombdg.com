<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class isAdmin implements FilterInterface
{
  public function before(RequestInterface $request,  $arguments = null)
  {

  }

  public function after(RequestInterface $request, ResponseInterface $response,  $arguments = null)
  {
    if (session('authenticated') && session('role') != 'admin') {
      return redirect()->to(base_url());
    } 
  }
}
