<?php

namespace App\Controllers;

use App\Libraries\Miktik;
use PhpParser\Node\Stmt\Return_;

class Home extends BaseController
{

    protected $data, 
    $miktik_conn, 
    $validation, 
    $request,
    $session;

    function __construct()
    {
        $this->validation =  \Config\Services::validation();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->session->setTempdata('logged', false, 600);
    }

    public function index()
    {
        // dd($this->session->getTempdata('logged'));
        if ($this->session->getTempdata('logged')){
            return view('home');
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
            // $obj = new Miktik();
            // if ($obj->connect('192.168.11.126', 'user1', 'user1'))
            // {
            //     $this->miktik_conn = $obj;
            // }
            $this->session->setTempdata('logged', true, 600);
            // $this->session->set('logged');
            $this->session->markAsTempdata($this->request->getPost(), 600);
            // dd($_SESSION);
            return view('home');
        }
    }

    public function logout()
    {
        dd($this->session->getTempdata('logged'));
        // if ($this->session->getTempdata('logged')){
        // }
        $this->session->removeTempdata('ip_mikrotik');
        $this->session->removeTempdata('username');
        $this->session->removeTempdata('password');
        // $this->session->setTempdata('logged', false);
        return view('/login_page', [
            'validation' => $this->validation
        ]);
    }

    public function defRoute()
    {
        $d = $this->miktik_conn->comm("/ip/route/print", array(
            "where" => "",
            "?dst-address" => "0.0.0.0/0",
            "?active" => "true",
        ));

        $interface = explode("via ", $d[0]['gateway-status'])[1];

        $ip_public = $this->miktik_conn->comm("/ip/address/print", array(
            "where" => "",
            "?interface" => $interface,
        ));

        var_dump($ip_public);

        // for ($i=0; $i < 9; $i++){
            
        //     if ($d[$i]['dst-address'] == "0.0.0.0/0" and $d[$i]['active'] == "true"){
        //         echo json_encode($d[$i]);
        //     }
        // }

        $this->miktik_conn->disconnect();
    }

    public function interfaces()
    {
        if ($this->miktik_conn->write("/interface/print")){
            echo json_encode($this->miktik_conn->comm("/interface/print"));
        }

        $this->miktik->disconnect();

    }

    public function dhcpBound()
    {
        $usrAct = $this->miktik_conn->comm("/ip/dhcp-server/lease/print", array(
            "where" => "",
            "?status" => "bound",
            "?server" => "dhcp4"
            // "without-paging" => "",
            // "active" => ""
        ));

        var_dump($usrAct);
    }
}
