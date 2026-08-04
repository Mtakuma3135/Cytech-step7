<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Auth::attempt() はログイン成功時にセッションIDを再生成してしまうため、
        // ゲストカートを引き当てるためのセッションIDはログイン前に控えておく
        $guestSessionId = $request->session()->getId();

        $request->authenticate();

        $this->cart->mergeGuestCartIntoUser($request->user()->id, $guestSessionId);

        $request->session()->regenerate();

        // 管理者はデフォルトで商品管理画面へ、一般顧客はショップトップへ
        $home = $request->user()->isAdmin() ? '/products' : RouteServiceProvider::HOME;

        return redirect()->intended($home);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
