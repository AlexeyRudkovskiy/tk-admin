<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 20.07.18
 * Time: 21:48
 */

namespace ARudkovskiy\Admin\Http\Middleware;

use Closure;

class AdminMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (session()->has('user_id')) {
            return $next($request);
        }

        session()->put('_redirect_to', $request->getRequestUri());

        return redirect()->route('admin.signin');
    }

}
