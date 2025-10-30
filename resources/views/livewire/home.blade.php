<div class="w-full h-full bg-white text-gray-800">

    {{-- Header --}}
    <header class="md:hidden sticky top-0 bg-white shadow-sm z-10">
        <div class="grid grid-cols-12 gap-2 items-center p-2">
            {{-- Logo --}}
            <div class="col-span-3">
                <img src="{{ asset('assets/logo.png') }}" class="h-12 max-w-lg w-full object-contain" alt="logo">
            </div>

            {{-- Search --}}
            <div class="col-span-8 flex justify-center px-2">
                <input 
                    type="text" 
                    placeholder="Search"
                    class="border-0 outline-none w-full focus:outline-none bg-gray-100 rounded-lg focus:ring-0 hover:ring-0 px-3 py-2 text-sm"
                >
            </div>

            {{-- Heart Icon --}}
            <div class="col-span-1 flex justify-center">
                <a href="#" class="text-gray-700 hover:text-red-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </a>
            </div>
        </div>
    </header>

    {{-- Main Section --}}
    <main class="grid lg:grid-cols-12 gap-8 md:mt-10 px-2 md:px-6">

        {{-- Feed Section --}}
        <aside class="lg:col-span-8 overflow-hidden">

            {{-- Stories --}}
            <section class="mt-4">
                <ul class="flex overflow-x-auto scrollbar-hide items-center gap-3 p-2">
                    @for ($i = 0; $i < 10; $i++)
                        <li class="flex flex-col justify-center w-20 gap-1 p-2">
                            <x-avatar 
                                src="https://source.unsplash.com/random/500x500?face&sig={{ $i }}" 
                                class="h-14 w-14 rounded-full object-cover ring-2 ring-pink-500"
                                alt="Story Avatar"/>
                            <p class="text-xs font-medium truncate text-center">{{ fake()->name }}</p>
                        </li>
                    @endfor
                </ul>
            </section>

            {{-- Posts --}}
            <section class="mt-5 space-y-4 p-2">
                <livewire:post.item/>
            </section>

            {{-- Example Post Placeholder --}}
            <section class="mt-6 text-center text-gray-500">
                <p>No posts yet. Start following people to see their posts!</p>
            </section>
        </aside>

        {{-- Suggestions Sidebar --}}
        <aside class="lg:col-span-4 hidden lg:block p-4">

            {{-- Current User --}}
            <div class="flex items-center gap-3">
                <img src="https://source.unsplash.com/random/500x500?face&sig=user" class="w-12 h-12 rounded-full object-cover" alt="User Avatar">
                <h4 class="font-medium text-sm">{{ fake()->name }}</h4>
            </div>

            {{-- Suggestions List --}}
            <section class="mt-6">
                <h4 class="font-bold text-gray-700 mb-3">Suggestions for you</h4>
                <ul class="space-y-3">
                    @for ($i = 0; $i < 5; $i++)
                        <li class="flex items-center gap-3">
                            <x-avatar 
                                src="https://source.unsplash.com/random/500x500?face&sig=sug{{ $i }}" 
                                class="w-12 h-12 rounded-full object-cover" 
                                alt="Suggested User"/>
                            <div class="grid grid-cols-7 w-full gap-2">
                                <div class="col-span-5">
                                    <h5 class="font-semibold truncate text-sm">{{ fake()->name }}</h5>
                                    <p class="text-xs truncate text-gray-500">Followed by {{ fake()->firstName }}</p>
                                </div>
                                <div class="col-span-2 flex justify-end">
                                    <button class="font-bold text-blue-500 text-sm">Follow</button>
                                </div>
                            </div>
                        </li>
                    @endfor
                </ul>
            </section>

            {{-- Footer Links --}}
            <section class="mt-10">
                <ol class="flex gap-3 flex-wrap text-xs text-gray-700 font-medium">
                    <li><a href="#" class="hover:underline">About</a></li>
                    <li><a href="#" class="hover:underline">Help</a></li>
                    <li><a href="#" class="hover:underline">API</a></li>
                    <li><a href="#" class="hover:underline">Jobs</a></li>
                    <li><a href="#" class="hover:underline">Privacy</a></li>
                    <li><a href="#" class="hover:underline">Terms</a></li>
                    <li><a href="#" class="hover:underline">Locations</a></li>
                </ol>
                <h3 class="text-gray-600 mt-6 text-sm text-center">
                    © {{ date('Y') }} INSTAGRAM COURSE
                </h3>
            </section>
        </aside>
    </main>
</div>
