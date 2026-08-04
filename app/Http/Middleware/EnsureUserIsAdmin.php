<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * 管理者（商品管理・メーカー管理画面）専用ルートを守るミドルウェア
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isAdmin(), 403, 'この画面にアクセスする権限がありません。');

        return $next($request);
    }
}
