<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Message;


class Sample extends Controller {
    public $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }
    public function run(string $message): void {
        $this->message->send($message);
    }
}
