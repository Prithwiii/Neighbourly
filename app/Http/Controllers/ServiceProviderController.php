<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderController extends Controller
{
    /**
     * Public listing of only verified providers.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::with('user')
            ->withCount('reviews')
            ->where('verification_status', 'verified');

        $newProviderRequests = 0;
        if (Auth::check() && Auth::user()->isAdmin()) {
            $newProviderRequests = ServiceProvider::whereIn('verification_status', ['pending', 'flagged'])
                ->whereNull('admin_seen_at')
                ->count();
        }

        if ($request->filled('category')) {
            $query->where('service_category', 'like', '%'.$request->string('category').'%');
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%'.$request->string('location').'%');
        }

        $providers = $query->latest()->paginate(12)->withQueryString();

        return view('service-providers.index', compact('providers', 'newProviderRequests'));
    }

    /**
     * Show one provider profile.
     */
    public function show(ServiceProvider $serviceProvider)
    {
        if (!$serviceProvider->isVerified()) {
            abort(404);
        }

        $serviceProvider->load(['user', 'reviews.user'])->loadCount('reviews');

        return view('service-providers.show', compact('serviceProvider'));
    }

    /**
     * Show provider registration form.
     */
    public function create()
    {
        $existing = Auth::user()->serviceProviderProfile;
        if ($existing) {
            return redirect()->route('providers.application')
                ->with('info', 'You already submitted a provider profile.');
        }

        $categories = [
            'Plumber',
            'Electrician',
            'Carpenter',
            'Painter',
            'AC Technician',
            'Cleaner',
            'Tutor',
            'Other',
        ];

        return view('service-providers.create', compact('categories'));
    }

    /**
     * Store provider self-registration with simple fraud checks.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'service_category' => 'required|string|max:120',
            'location' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:80',
            'description' => 'required|string|max:2000',
            'availability_status' => 'required|in:available,busy,offline',
            'availability_details' => 'nullable|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $validated['phone'] = $this->normalizeBangladeshPhone($validated['phone']);

        if (Auth::user()->serviceProviderProfile) {
            return back()->withErrors(['full_name' => 'You already have a provider profile.'])->withInput();
        }

        $fraudSignals = [];

        if (ServiceProvider::where('phone', $validated['phone'])->exists()) {
            $fraudSignals[] = 'Duplicate phone found in another provider application.';
        }

        $todayCount = ServiceProvider::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayCount >= 2) {
            $fraudSignals[] = 'Multiple registrations from same account in a single day.';
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('provider-documents', 'public');
        }

        $otp = (string) random_int(100000, 999999);

        $provider = ServiceProvider::create([
            'user_id' => Auth::id(),
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'service_category' => $validated['service_category'],
            'location' => $validated['location'],
            'experience_years' => $validated['experience_years'],
            'description' => $validated['description'],
            'availability_status' => $validated['availability_status'],
            'availability_details' => $validated['availability_details'] ?? null,
            'document_path' => $documentPath,
            'verification_status' => empty($fraudSignals) ? 'pending' : 'flagged',
            'phone_otp' => $otp,
            'fraud_note' => empty($fraudSignals) ? null : implode(' ', $fraudSignals),
        ]);

        return redirect()->route('providers.application')
            ->with('success', 'Provider profile submitted. Status: '.$provider->verification_status)
            ->with('otp_demo', 'Demo OTP for now: '.$otp);
    }

    /**
     * Show current logged-in provider application.
     */
    public function application()
    {
        $provider = Auth::user()->serviceProviderProfile;

        if (!$provider) {
            return redirect()->route('providers.create')
                ->with('info', 'Please submit provider registration first.');
        }

        return view('service-providers.application', compact('provider'));
    }

    /**
     * Demo OTP verification endpoint.
     */
    public function verifyPhone(Request $request, ServiceProvider $serviceProvider)
    {
        if ($serviceProvider->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if ($serviceProvider->phone_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code.']);
        }

        $serviceProvider->update([
            'phone_verified_at' => now(),
            'phone_otp' => null,
        ]);

        return back()->with('success', 'Phone verified successfully.');
    }

    /**
     * Provider can update availability after profile creation.
     */
    public function updateAvailability(Request $request, ServiceProvider $serviceProvider)
    {
        if ($serviceProvider->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'availability_status' => 'required|in:available,busy,offline',
            'availability_details' => 'nullable|string|max:255',
        ]);

        $serviceProvider->update($validated);

        return back()->with('success', 'Availability updated.');
    }

    private function normalizeBangladeshPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', trim($phone));

        if ($digits === '') {
            return '+880';
        }

        if (str_starts_with($digits, '880')) {
            $digits = substr($digits, 3);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '+880'.$digits;
    }
}
