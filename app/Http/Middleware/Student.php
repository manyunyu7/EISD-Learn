<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if (Auth::check() && Auth::user()->role == 'student') {
         return $next($request);
       }else {
         abort('401','Halaman Ini Hanya Bisa Diakses Oleh Siswa');
       }
      return redirect(‘/’);
    }
}
