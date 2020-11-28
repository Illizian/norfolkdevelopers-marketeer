<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div>
            <x-application-logo class="h-24" />
        </div>

        <div class="py-12 text-4xl font-bold hover:text-red-500">
            <a href="{{ config('nova.path') }}">
                Enter &Rarr;
            </a>
        </div>
    </div>
</x-guest-layout>
