@extends('layouts.app')

@section('content')



<!-- CENTER HUB -->
<div class="flex items-center justify-center min-h-screen -mt-14">

    <!-- GLASS CARD -->
    <div class="w-[650px] p-10 rounded-3xl
                bg-white/20 backdrop-blur-xl
                border border-white/30
                shadow-2xl">

        

        <div class="grid grid-cols-2 gap-5">

            <a href="{{ route('lost-items.hub') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Lost & Found
            </a>

            <a href="{{ route('announcements.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Announcements
            </a>

            <a href="{{ route('issues.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Issues
            </a>

            <a href="{{ route('marketplace.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Marketplace
            </a>

            <a href="{{ route('messages.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Messages
            </a>

            <a href="{{ route('map') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Map
            </a>

            <a href="{{ route('news.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerland">
                News
            </a>
            
            <a href="{{ route('services.index') }}"
               class="p-5 rounded-2xl bg-white/30 hover:bg-white/40
                      backdrop-blur-md border border-white/30
                      shadow-md hover:shadow-xl transition text-center text-emerald-900">
                Local Services
            </a>

        </div>

    </div>

</div>

@endsection