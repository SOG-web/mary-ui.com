<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new
#[Title('Choices')]
#[Layout('components.layouts.app', ['description' => 'Livewire UI full featured Choices component with searchable, multi-selection support and customizable slots.'])]
class extends Component {
    public ?int $user_id = 1;

    public array $users_multi_ids = [3, 4];

    public int $user_custom_id = 1;

    public array $users_all_ids = [2];

    public array $users_all2_ids = [3];

    public array $users_compact_ids = [1];

    public array $users_compact2_ids = [2, 3, 4];

    public array $users_all_compact_ids = [];

    public ?int $user_searchable_id = null;

    public ?int $user_searchable_min_chars_id = null;

    public array $users_multi_searchable_ids = [];

    public int $user_custom_slot_id = 1;

    public Collection $users;

    public Collection $usersSearchable;

    public Collection $usersSearchableMinChars;

    public Collection $usersMultiSearchable;

    public function mount()
    {
        // For NOT SEARCHABLE examples, static list
        $this->users = User::with('city')->take(4)->get();

        // Single searchable
        $this->search();

        // Single searchable (min chars)
        $this->searchMinChars();

        // Multiple Searchable
        $this->searchMulti();
    }

    public function save()
    {
        $this->validate();
    }

    // For single searchable
    public function search(string $value = '')
    {
        // Besides the search results, you must include on demand selected option
        $selectedOption = User::where('id', $this->user_searchable_id)->get();

        $this->usersSearchable = User::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOption);     // <-- Adds selected option
    }

    // For multi searchable
    public function searchMulti(string $value = '')
    {
        // Besides the search results, you must include on demand selected options
        $selectedOptions = User::whereIn('id', $this->users_multi_searchable_ids)->orderBy('name')->get();

        $this->usersMultiSearchable = User::query()
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->orderBy('name')
            ->get()
            ->merge($selectedOptions);     // <-- Adds selected options
    }

    // For single searchable (min chars)
    public function searchMinChars(string $value = '')
    {
        // Besides the search results, you must include on demand selected options
        $selectedOption = User::where('id', $this->user_searchable_min_chars_id)->orderBy('name')->get();

        $this->usersSearchableMinChars = collect();

        if (strlen($value) >= 2) {
            $this->usersSearchableMinChars = User::query()
                ->where('name', 'like', "%$value%")
                ->take(5)
                ->orderBy('name')
                ->get();
        }

        $this->usersSearchableMinChars = $this->usersSearchableMinChars->merge($selectedOption);     // <-- Adds selected option
    }
}
?>

<div class="docs">
    <x-anchor title="Choices" />

    <p>
        This component is intended to be used to build complex selection interfaces for single and multiple values. It also supports <strong>async search</strong> when dealing with
        large lists.
    </p>

    <x-alert icon="o-light-bulb" class="markdown mb-10">
        Most of time you just need a simple <a href="/docs/components/select" wire:navigate>Select</a> component, which renders nice natively on every device.
    </x-alert>

    <x-anchor title="Selection" size="text-2xl" class="mt-10 mb-5" />

    <p>
        By default, it will look up for:
    </p>

    <ul>
        <li><code>$object->id</code> for option value.</li>
        <li><code>$object->name</code> for option display label.</li>
        <li><code>$object->avatar</code> for avatar picture.</li>
    </ul>

    <br>

    <x-code class="grid gap-5">
        @verbatim('docs')
            @php                              // [tl! .docs-hide]
                    $users = $this->users;   // [tl! .docs-hide]
            @endphp                         {{-- [tl! .docs-hide] --}}
            {{-- Notice `single` --}}
            <x-choices label="Single" wire:model="user_id" :options="$users" single />

            {{-- public array $users_multi_ids = []; --}}
            <x-choices label="Multiple" wire:model="users_multi_ids" :options="$users" />

            {{-- Custom options --}}
            <x-choices
                label="Custom options"
                wire:model="user_custom_id"
                :options="$users"
                option-label="username"
                option-sub-label="city.name"
                option-avatar="other_avatar"
                icon="o-users"
                height="max-h-96" {{-- Default is `max-h-64`  --}}
                hint="It has custom options"
                single />
        @endverbatim
    </x-code>

    <x-anchor title="Select All" size="text-2xl" class="mt-10 mb-5" />

    <p>
        This option only works for <strong>multiple and non-searchable</strong> exclusively.
    </p>

    <br>

    <x-code class="grid gap-5">
        @verbatim('docs')
            @php                              // [tl! .docs-hide]
                    $users = $this->users;   // [tl! .docs-hide]
            @endphp                         {{-- [tl! .docs-hide] --}}
            {{-- Notice `allow-all` --}}
            <x-choices label="Multiple" wire:model="users_all_ids" :options="$users" allow-all />

            <x-choices
                label="Multiple"
                wire:model="users_all2_ids"
                :options="$users"
                allow-all
                allow-all-text="Select all stuff"
                remove-all-text="Delete all things" />
        @endverbatim
    </x-code>

    <x-anchor title="Compact mode" size="text-2xl" class="mt-10 mb-5" />

    <p>
        This option only works for <strong>multiple and non-searchable</strong> exclusively.
    </p>

    <br>

    <x-code class="grid gap-5">
        @verbatim('docs')
            @php                              // [tl! .docs-hide]
                    $users = $this->users;   // [tl! .docs-hide]
            @endphp                         {{-- [tl! .docs-hide] --}}
            {{-- Notice `compact` --}}
            <x-choices label="Compact" wire:model="users_compact_ids" :options="$users" compact />

            <x-choices
                label="Compact label"
                wire:model="users_compact2_ids"
                :options="$users"
                compact
                compact-text="stuff chosen" />
        @endverbatim
    </x-code>

    <br>

    <p>
        You can combine <code>allow-all</code> and <code>compact</code>
    </p>

    <x-code class="grid gap-5">
        @verbatim('docs')
            @php                              // [tl! .docs-hide]
                    $users = $this->users;   // [tl! .docs-hide]
            @endphp                         {{-- [tl! .docs-hide] --}}
            <x-choices
                label="Select All + Compact"
                wire:model="users_all_compact_ids"
                :options="$users"
                compact
                allow-all />
        @endverbatim
    </x-code>

    <x-anchor title="Searchable" size="text-2xl" class="mt-10 mb-5" />

    <p>
        When dealing with large options list use <code>searchable</code> parameter. By default, it calls <code>search()</code> method to get fresh options while typing.
        You can change the method's name by using <code>search-function</code> parameter.
    </p>

    <br>

    <x-code class="grid gap-5">
        @verbatim('docs')
            @php                                                                  // [tl! .docs-hide]
                    $usersSearchable = $this->usersSearchable;                    // [tl! .docs-hide]
                    $usersMultiSearchable = $this->usersMultiSearchable;          // [tl! .docs-hide]
            @endphp                                                               {{-- [tl! .docs-hide] --}}
            {{-- Notice `searchable` + `single` --}}
            <x-choices
                label="Searchable + Single"
                wire:model="user_searchable_id"
                :options="$usersSearchable"
                single
                searchable />

            {{-- Notice custom `search-function` --}}
            <x-choices
                label="Searchable + Multiple"
                wire:model="users_multi_searchable_ids"
                :options="$usersMultiSearchable"
                search-function="searchMulti"
                no-result-text="Ops! Nothing here ..."
                searchable />
        @endverbatim
    </x-code>

    <br>

    <p>
        You must also consider displaying pre-selected items on list, when it <strong>first renders</strong> and <strong>while searching</strong>.
        There are many approaches to make it work, but here is an example for <strong>single search using Volt.</strong>
    </p>

    {{--@formatter:off--}}
    <x-code no-render language="php">
        @verbatim('docs')
            new class extends Component {

                // Selected option
                public ?int $user_searchable_id = null;

                // Options list
                public Collection $usersSearchable;

                public function mount()
                {
                    // Fill options when component first renders
                    $this->search();
                }

                // Also called as you type
                public function search(string $value = '')
                {
                    // Besides the search results, you must include on demand selected option
                    $selectedOption = User::where('id', $this->user_searchable_id)->get();

                    $this->usersSearchable = User::query()
                        ->where('name', 'like', "%$value%")
                        ->take(5)
                        ->orderBy('name')
                        ->get()
                        ->merge($selectedOption);     // <-- Adds selected option
                }
            }
        @endverbatim
    </x-code>
    {{--@formatter:on--}}

    <br>

    <p>
        Sometimes you don't want to hit a datasource on <strong>every keystroke</strong>.
        So, you can make use of <code>debounce</code> as described at <a href="https://livewire.laravel.com/docs/forms#debouncing-input" target="_blank">Livewire docs</a>
        for input fields.
    </p>

    <p>
        Another approach is to use <code>min-chars</code> attribute to avoid hit <strong>search method</strong> itself until you have typed such amount of chars.
    </p>

    <br>

    <x-code>
        @verbatim('docs')
            @php                                                              // [tl! .docs-hide]
                $usersSearchableMinChars = $this->usersSearchableMinChars;    // [tl! .docs-hide]
            @endphp                                                           {{-- [tl! .docs-hide] --}}
            {{-- Notice `min-chars` and `debounce` --}}
            <x-choices
                label="Searchable + Single + Debounce + Min chars"
                wire:model="user_searchable_min_chars_id"
                :options="$usersSearchableMinChars"
                search-function="searchMinChars"
                debounce="300ms" {{-- Default is `250ms`--}}
                min-chars="2" {{-- Default is `0`--}}
                single
                searchable />
        @endverbatim
    </x-code>

    <br>

    <x-anchor title="Slots" size="text-2xl" class="mt-10 mb-5" />

    <p>
        You have full control on rendering items by using the <code>&#x40;scope('item', $object)</code> special blade directive.
        It injects the current <code>$object</code> from the loop's context and achieves the same behavior that you would expect from the Vue/React scoped slots.
    </p>

    <p>
        You can customize the list item and selected item slot.
    </p>

    {{--@formatter:off--}}
    <x-code>
        @verbatim('docs')
            <div>                                       <!-- [tl! .docs-hide] -->
                @php $users = $this->users @endphp      <!-- [tl! .docs-hide] -->
            </div>                                      <!-- [tl! .docs-hide] -->
            <x-choices label="Slots" wire:model="user_custom_slot_id" :options="$users" single>
                {{-- Item slot--}}
                @scope('item', $user)
                    <x-list-item :item="$user" sub-value="bio">
                        <x-slot:avatar>
                            <x-icon name="o-user" class="bg-orange-100 p-2 w-8 h8 rounded-full" />
                        </x-slot:avatar>
                        <x-slot:actions>
                            <x-badge :value="$user->username" />
                        </x-slot:actions>
                    </x-list-item>
                @endscope

                {{-- Selection slot--}}
                @scope('selection', $user)
                    {{ $user->name }} ({{ $user->username }})
                @endscope
            </x-choices>
        @endverbatim
    </x-code>
    {{--@formatter:on--}}
</div>
