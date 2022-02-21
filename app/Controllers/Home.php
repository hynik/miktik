<?php

namespace App\Controllers;

use PhpParser\Node\Stmt\Return_;

class Home extends BaseController
{

    protected $data;

    public function index()
    {
        return view('welcome_message');
    }

    public function interfaces()
    {
        if ($this->miktik->connect('192.168.11.126', 'user1', 'user1')) {
            $result = $this->miktik->comm('/interface/print');
            $data['total_interface'] = $result;
        }

        $this->miktik->disconnect();

        return view('interfaces', $data);
    }

    public function userAct()
    {
        $server=false;
        if ($this->miktik->connect('192.168.11.126', 'user1', 'user1')){
            $leases = $this->miktik->comm('/ip/dhcp-server/lease/print');

            $this->data['server_dhcp'] = count($this->miktik->comm('/ip/dhcp-server/print'));
            
            foreach($leases as $data){
                if ($server){
                    if ($data['server'] == $server){
                        $data;
                    }
                }else{
                    $data;
                }
            }

            $this->miktik->disconnect();
        }
        // var_dump($this->data['server_dhcp']);
        return view('leases', $this->data);
    }
}
