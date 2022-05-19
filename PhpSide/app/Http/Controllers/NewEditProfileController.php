<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Illuminate\Http\Request;

class NewEditProfileController extends Controller
{
    public array $data;

    public function __construct(){
        $this->data = json_decode(file_get_contents(storage_path() . '/configs.json'), true);
    }
    public function index(): Factory|View|Application
    {
        return view("editProfile.editProfile", [
                "home" => $this->data['address'],
                "nodePort" => $this->data['nodePort'],
                "phpPort" => $this->data['phpPort'],
                "route" => "edit"
            ]);
    }
}
