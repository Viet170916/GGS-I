<?php
namespace App\Http\Controllers\auth;

use Tymon\JWTAuth\Token;
use App\Constants\Code\HTTPStatusCodes;
use App\Constants\Messages\ErrorMessages;
use App\Constants\Messages\SuccessfulMessages;
use App\Models\Email\LoginEmail;
use App\Models\User;
use Exception;
use App\Models\Responses\GenericResponseModel;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\UniqueConstraintViolationException;

class AuthController extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function login( Request $request ): JsonResponse {
        $validator = Validator::make( $request->all(), [
            'email' => 'required|email',
        ] );
        if( $validator->fails() ) {
            return response()->json( [ 'error' => ErrorMessages::INVALID_EMAIL ], 400 );
        }
        try {
            User::create( [
                'email' => $request->input( "email" ),
                'name' => $request->input( 'email' ),
            ] );
        } catch( UniqueConstraintViolationException ) {
        }
        try {
            $user = User::where( 'email', $request->input( "email" ) )->get()[ 0 ];
            $token = new Token( JWTAuth::fromUser( $user, [ 'expires_in' => 3000 ] ) );
            Mail::to( $request->input( 'email' ) )->send( new LoginEmail( $token ) );
        } catch( Exception ) {
            return response()->json( GenericResponseModel::Error( ErrorMessages::LOGIN_FAIL ), HTTPStatusCodes::NOT_FOUND );
        }
        return response()->json( GenericResponseModel::Success( SuccessfulMessages::LOGIN_EMAIL_SENT_SUCCESSFUL ) );
    }
    public function verifyLogin(): ?JsonResponse {
        try {
            $token = JWTAuth::parseToken();
            $payload = $token->getPayload();
//            JWTAuth::invalidate( $token );
            $user = User::find( (int)$payload[ 'sub' ] );
//            $currentToken = JWTAuth::fromUser( $user, [ 'expires_in' => 3000 ] );
            return response()->json( GenericResponseModel::Error( "", null, $user ) );
        } catch( TokenBlacklistedException|TokenExpiredException ) {
            return response()->json( GenericResponseModel::Error( ErrorMessages::TOKEN_EXPIRED ), HTTPStatusCodes::UNAUTHORIZED );
        }
//        catch( Exception ) {
//            return response()->json( GenericResponseModel::Error( ErrorMessages::TOKEN_INVALID ), HTTPStatusCodes::UNAUTHORIZED );
//        }
    }
    public function isLogin(): array {
        return [ 'is-login' => true ];
    }
}
