<?php


namespace App\Http\Middleware;


use Illuminate\Http\Request;

class ForceHttps
{

    protected $headers = Request::HEADER_X_FORWARDED_ALL;
    public function handle(Request $request, \Closure $closure){
        $request->setTrustedProxies([$request->getClientIp()], $this->headers);
        return redirect()->secure($request->getRequestUri());
    }
}
