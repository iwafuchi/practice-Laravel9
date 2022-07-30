<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TestClassBase extends Component {
    public $classBaseMessage;
    public $sum;
    public $num1;
    public $num2;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $classBaseMessage = "初期値です", int $num1 = 0, int $num2 = 0) {
        //
        $this->classBaseMessage = $classBaseMessage;
        $this->num1 = $num1;
        $this->num2 = $num2;
        $this->sum = $num1 + $num2;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render() {
        return view('components.tests.test-class-base-component');
    }
}
