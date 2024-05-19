<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class VerifyFirebaseToken
{
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();
        if (!$request->bearerToken()) {
            return response()->json(['Token ini dibutuhkan'], 401);
        }

        try {
            $decoded = JWT::decode($jwt, new Key(env('FIREBASE_SECRET_KEY'), 'HS256'));
        } catch (ExpiredException $e) {
            return response()->json(['Token sudah kadaluarsa'], 401);
        } catch (SignatureInvalidException $e) {
            return response()->json(['Token sudah invalid'], 401);
        } catch (BeforeValidException $e) {
            return response()->json(['Token ini belum valid'], 401);
        } catch (\Exception $e) {
            return response()->json(['Token invalid'], 401);
        }

        $request->attributes->add(['decoded_token' => $decoded]);

        return $next($request);
    }
}