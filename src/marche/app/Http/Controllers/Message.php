<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Message extends Controller {
    public function send($userName, string $text): void {
        echo "${userName}さんは${text}";
    }
}
