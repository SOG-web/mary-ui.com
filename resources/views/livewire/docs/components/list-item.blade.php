<?php

use Livewire\Volt\Component;

new class extends Component
{
    public function delete()
    {
        sleep(1);
    }
}

?>

<div>

<x-markdown class="markdown">
# List Item

It will lookup for:

- `$object->name` as main value.
-  `$object->avatar` as picture url.

<br>
</x-markdown>

<x-code>
@verbatim
@php
    $users = App\Models\User::take(3)->get();
@endphp 

@foreach($users as $user)
    <x-list-item :item="$user" link="/docs/installation" />
@endforeach
@endverbatim
</x-code>

<x-markdown >
### Slots and other attributes
<br>
</x-markdown>

<x-code>
@verbatim
@php
    $user1 = App\Models\User::inRandomOrder()->first();
    $user2 = App\Models\User::inRandomOrder()->first();    
@endphp 

<x-list-item :item="$user1" value="other_name" sub-value="other_email" avatar="other_avatar" />    

<x-list-item :item="$user2" no-separator>
    <x-slot:value>
        Custom value
    </x-slot:value>
    <x-slot:sub-value>
        Custom sub-value
    </x-slot:sub-value>
    <x-slot:actions>
        <x-button icon="o-trash" class="text-red-500" wire:click="delete(1)" spinner />
    </x-slot:actions>
</x-list-item>
@endverbatim
</x-code>

</div>