<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Message extends Controller {
    public function send(string $message): void {
        echo $message;
    }
}
