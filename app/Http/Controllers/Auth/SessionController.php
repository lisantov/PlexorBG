<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthenticationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SessionController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authentication,
    ) {}

    public function create(): Response
    {
        return Inertia::render('auth/Login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $this->authentication->login(
            $request,
            $request->string('identifier')->toString(),
            $request->string('password')->toString(),
            $request->boolean('remember'),
        );

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->authentication->logout($request);

        return redirect()->route('home');
    }
}
