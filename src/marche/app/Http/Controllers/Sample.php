<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Message;


class Sample extends Controller {
    public $userName = "user";
    public $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function run(string $text): void {
        $this->message->send($this->userName, $text);
    }
}
