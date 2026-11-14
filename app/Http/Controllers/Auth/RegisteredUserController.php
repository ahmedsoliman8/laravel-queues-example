<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\CreateUserProfile;
use App\Jobs\CreateUserSetting;
use App\Jobs\NotifyAdmin;
use App\Jobs\SendWelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // SendWelcomeEmail::dispatch($user);
        //   CreateUserProfile::dispatch($user);
        // CreateUserSetting::dispatch($user);
        // NotifyAdmin::dispatch($user)->delay(now()->addSeconds(30));

        Bus::chain([
            new CreateUserProfile($user),
            new CreateUserSetting($user),
            new SendWelcomeEmail($user),
            new NotifyAdmin($user)
        ])->catch(function (Throwable $e) use ($user) {
            logger()->error("Chain failed for User {$user->id}: " . $e->getMessage());
        })->dispatch();
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
