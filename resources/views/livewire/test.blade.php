<?php

use Livewire\Volt\Component;

new class extends Component {
    public function render(): string {
        return <<<'HTML'
            <h1>TEST WORKS</h1>
        HTML;
    }
};
