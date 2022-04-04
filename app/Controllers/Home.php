<?php

namespace App\Controllers;

use App\Libraries\Miktik;
use PhpParser\Node\Stmt\Return_;

class Home extends BaseController
{

    protected $data, $miktik_conn;

    function __construct()
    {

        $obj = new Miktik();
        if ($obj->connect('192.168.11.126', 'user1', 'user1'))
        {
            $this->miktik_conn = $obj;
        }

    }

    public function index()
    {
        return view("Home");

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
