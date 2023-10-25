<?php

use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Title('Upgrading')] class extends Component {
}; ?>

<div class="docs">
    <x-anchor title="Upgrading" />

    <p>
        Nothing special here, just a reminder:
    </p>

    <ul>
        <li>
            You should keep an eye on Mary's <a href="https://github.com/robsontenorio/mary/releases">releases pages</a> to stay updated.
        </li>
        <li>
            As Mary uses <strong>daisyUI</strong> and <strong>Tailwind</strong> you should consider as well upgrade regularly their NPM packages.
        </li>
    </ul>

    <x-anchor title="Recent releases" size="text-2xl" class="mt-10 mb-5" />

    <livewire:releases lazy />
</div>