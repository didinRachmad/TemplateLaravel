<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 fixed-top shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img class="h-8 w-auto" src="{{ asset('icons/icon.svg') }}" alt="Logo">
                    </a>
                </div>

                <!-- Desktop Navigation Menu -->
                @foreach ($menuTree as $menu)
                    @if ($menu->children->isNotEmpty())
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                            <div x-data="{ open: false }" class="relative">
                                <x-dropdown align="left" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                                            @if ($menu->icon)
                                                <i class="{{ $menu->icon }} mr-1"></i>
                                            @endif
                                            {{ __($menu->title) }}
                                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        @foreach ($menu->children as $child)
                                            @if ($child->children->isNotEmpty())
                                                <!-- Jika sub-menu memiliki dropdown (kedalaman > 1) -->
                                                <div x-data="{ open: false }" class="relative">
                                                    <x-dropdown-link href="javascript:void(0)"
                                                        @click.prevent="open = !open">
                                                        @if ($child->icon)
                                                            <i class="{{ $child->icon }} mr-1"></i>
                                                        @endif
                                                        {{ __($child->title) }}
                                                        <svg class="ml-1 h-4 w-4 inline-block"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </x-dropdown-link>
                                                    <div x-show="open" @click.away="open = false" class="ml-4">
                                                        @foreach ($child->children as $subchild)
                                                            <x-dropdown-link :href="url($subchild->route)" :active="isActiveMenu($subchild->route)"
                                                                class="{{ isActiveMenu($subchild->route) ? 'active-menu ' : '' }}">
                                                                @if ($subchild->icon)
                                                                    <i class="{{ $subchild->icon }} mr-1"></i>
                                                                @endif
                                                                {{ __($subchild->title) }}
                                                            </x-dropdown-link>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <x-dropdown-link :href="url($child->route)" :active="isActiveMenu($child->route)"
                                                    class="{{ isActiveMenu($child->route) ? 'active-menu ' : '' }}">
                                                    @if ($child->icon)
                                                        <i class="{{ $child->icon }} mr-1"></i>
                                                    @endif
                                                    {{ __($child->title) }}
                                                </x-dropdown-link>
                                            @endif
                                        @endforeach
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    @else
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="url($menu->route)" :active="isActiveMenu($menu->route)"
                                class="{{ isActiveMenu($menu->route) ? 'active-menu' : '' }} hover:text-gray-900">
                                @if ($menu->icon)
                                    <i class="{{ $menu->icon }} mr-1"></i>
                                @endif
                                {{ __($menu->title) }}
                            </x-nav-link>
                        </div>
                    @endif
                @endforeach
            </div>
            <!-- Right Side: Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ auth()->user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($menuTree as $menu)
                @if ($menu->children->isNotEmpty())
                    <div x-data="{ open: false }" class="border-t border-gray-200">
                        <button @click="open = !open" type="button"
                            class="w-full text-left px-4 py-2 flex justify-between items-center text-gray-700 hover:bg-gray-100">
                            @if ($menu->icon)
                                <i class="{{ $menu->icon }} mr-1"></i>
                            @endif
                            {{ __($menu->title) }}
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="space-y-1">
                            @foreach ($menu->children as $child)
                                <x-responsive-nav-link :href="url($child->route)" :active="isActiveMenu($child->route)"
                                    class="{{ isActiveMenu($child->route) ? 'active-menu ' : '' }}block pl-8 pr-4 py-2 text-gray-700 hover:bg-gray-100">
                                    @if ($child->icon)
                                        <i class="{{ $child->icon }} mr-1"></i>
                                    @endif
                                    {{ __($child->title) }}
                                </x-responsive-nav-link>
                            @endforeach
                        </div>
                    </div>
                @else
                    <x-responsive-nav-link :href="url($menu->route)" :active="isActiveMenu($menu->route)"
                        class="{{ isActiveMenu($menu->route) ? 'active-menu ' : '' }}block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        @if ($menu->icon)
                            <i class="{{ $menu->icon }} mr-1"></i>
                        @endif
                        {{ __($menu->title) }}
                    </x-responsive-nav-link>
                @endif
            @endforeach
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ auth()->user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
