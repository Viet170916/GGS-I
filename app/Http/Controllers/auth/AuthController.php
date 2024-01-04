<?php

namespace App\Http\Controllers\auth;

use App\Constants\Messages\ErrorMessages;
use App\Constants\Messages\SuccessfulMessages;
use App\Models\Email\LoginEmail;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use function Laravel\Prompts\password;

class AuthController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => ErrorMessages::INVALID_EMAIL], 400);
        }
        try {
            User::create([
                'email' => $request->input("email"),
                'name' => $request->input('email'),
            ]);
        } catch (Exception $exception) {
        }

        $user = User::where('email', $request->input("email"))->get()[0];
        $token = JWTAuth::fromUser($user);

        Mail::to($request->input('email'))->send(new LoginEmail($token)); // Gửi email

        return response()->json(['message' => SuccessfulMessages::LOGIN_EMAIL_SENT_SUCCESSFUL]);

    }

    public function verifyLogin(Request $request): JsonResponse
    {


        // Lấy dữ liệu từ token
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Token is valid', 'token' => $payload]);

//            return response()->json(['message' => 'Token has been invalidated']);
        }
        catch (TokenBlacklistedException $blacklistedException){
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        catch (Exception $e) {
            return response()->json(['error' => 'Token invalidation failed'], 401);
        }
//        $newToken = Token::createToken($JWT['sub']);
    }

}
