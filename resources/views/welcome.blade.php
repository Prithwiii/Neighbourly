
<x-guest-layout>
    <div class="min-h-screen flex flex-col bg-gray-100 font-sans">
        <nav class="bg-white shadow">
            <div class="flex items-center justify-between px-4 py-4 w-full">
                <a href="/" class="text-xl font-bold text-gray-900">Neighbourly</a>
                <div class="flex gap-2">
                    <a href="/login" class="px-4 py-2 text-gray-600 hover:text-gray-900">Sign In</a>
                    <a href="/register" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">Sign Up</a>
                </div>
            </div>
        </nav>
 
    <main class="flex-1 container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow rounded-lg mt-8">
                <div class="p-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Welcome to Neighbourly!</h1>
                        <p class="mt-4 text-gray-600">This is your brand new Laravel application. Time to make it
                            sing (or chirp)!</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
 
    <footer class="text-center p-5 bg-gray-200 text-gray-700 text-xs">
        <div>
            <p>© 2025 Neighbourly - Built with Laravel and ❤️</p>
        </div>
    </footer>
</x-guest-layout>

{{-- <h1>Neighbourly</h1>
<p>Welcome to our community portal.</p> --}}