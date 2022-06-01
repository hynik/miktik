<?php

namespace App\Controllers;

use App\Libraries\Miktik;
use PhpParser\Node\Stmt\Return_;

class Home extends BaseController
{

    protected $data, 
    $validation, 
    $request,
    $session,
    $miktik;

    function __construct()
    {
        $this->validation =  \Config\Services::validation();
        $this->request = \Config\Services::request();
        $this->miktik = new Miktik();

        $this->data = [
            'title' => 'Dashboard Miktik'
        ];

        session()->setTempdata('logged_api', false);
        session()->setTempdata('logged', false);

        if (!empty(session()->getTempdata())){

            if ($this->miktik->connect(
                session()->getTempdata('ip_mikrotik'),
                session()->getTempdata('username'),
                session()->getTempdata('password')
            )){
                session()->setTempdata('logged_api', true);
                $this->miktik = $this->miktik;
            }
        }
    }

    public function index()
    {

        $this->data = ['title' => 'Selamat Datang'];

        if (session()->getTempdata('logged')){
            return view('home', $this->data);
        }else{
            return view('login_page', [
                'validation' => $this->validation
            ]);
        }
    }

    public function attempLogin()
    {
        if (! $this->validate([
            'ip_mikrotik' => 'required',
            'username' => 'required',
            'password' => 'required',
        ])) {
            return view('login_page', [
                'validation' => $this->validator,
            ]);
        } else {

            //menambahkan data session untuk status login
            foreach($this->request->getPost() as $key => $val){
                session()->setTempdata($key, $val, 600);
            }

            //set key logged as true
            session()->setTempdata('logged', true, 600);

            return view('home', $this->data);
            
        }
    }

    public function logout()
    {

        session()->setTempdata([
            'logged' => false,
            'logged_api' => false
        ]);
        $this->miktik->disconnect();

        return view('/login_page', [
            'validation' => $this->validation
        ]);
    }

    public function defRoute()
    {
        if (session()->getTempdata('logged_api')){

            $d = $this->miktik->comm("/ip/route/print", array(
                "where" => "",
                "?dst-address" => "0.0.0.0/0",
                "?active" => "true",
            ));
    
            $interface = explode("via ", $d[0]['gateway-status'])[1];
    
            $ip_public = $this->miktik->comm("/ip/address/print", array(
                "where" => "",
                "?interface" => str_replace(" ", "", $interface),
            ));
            
            $a = [
                'status' => 200,
                'ip' => explode("/", $ip_public[0]['address'])[0],
                'interface' => str_replace(" ", "", $interface)
            ];

            $this->data['defRoute'] = $a;

            return json_encode($a);
        }else{
            return json_encode([
                'status' => 401,
                'message' => "anda belum login"
            ]);
        }

    }

    public function dhcpBound(String $server = null)
    {

        if (session()->getTempdata('logged_api')){
            
            $usrAct = $this->miktik->comm("/ip/dhcp-server/lease/print", array(
                "where" => "",
                "?status" => "bound",
                // "?server" => nullN
            ));
    
            dd($usrAct);
        }
    }

    public function resources()
    {
        if (session()->getTempdata('logged_api')){
            
            $resources = $this->miktik->comm("/system/resources/print");

            var_dump($resources);
        }else{
            return json_encode([
                'status' => 401,
                'message' => "anda belum login"
            ]);
        }
    }
}
