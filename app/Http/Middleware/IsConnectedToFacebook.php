<?php

namespace App\Http\Middleware;

use Closure;
use App;
use App\Token;
use App\User;
use Log;
use SammyK;

class IsConnectedToFacebook
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
        Log::info('Middleware IsConnectedToFacebook: Executing...');

        try {
            $fb = App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
            $token = $fb->getJavaScriptHelper()->getAccessToken();
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            Log::error('Middleware IsConnectedToFacebook: FacebookSDKException');
            Log::error($e->getMessage());
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            Log::warn('Middleware IsConnectedToFacebook: FacebookResponseException');
            Log::warn($e->getMessage());
            $token = '';
        }

        if (! $token) {
            Log::error('Middleware IsConnectedToFacebook: (JavaScript) $token is falsey.');
            return redirect('/');
        }

        $fb_user_token_row = Token::where('fb_user_token_temp', $token)->first();
        $fb_user_token_long = '';
        $fb_user_id = 0;

        if (! $fb_user_token_row || ! array_key_exists('fb_user_token_long', $fb_user_token_row) || ! array_key_exists('fb_user_id', $fb_user_token_row)) {
            Log::info('Middleware IsConnectedToFacebook: Token row does not exist for User.');
        } else {
            $fb_user_token_long = $fb_user_token_row['fb_user_token_long'];
            $fb_user_id = $fb_user_token_row['fb_user_id'];
        }

        if (! $fb_user_token_long) {
            try {
                $app_id = env('FACEBOOK_APP_ID', false);
                $app_secret = env('FACEBOOK_APP_SECRET', false);

                if (! $app_id || ! $app_secret) {
                    Log::error('Middleware IsConnectedToFacebook: Either FACEBOOK_APP_ID or FACEBOOK_APP_SECRET is falsey.');
                    $request->session()->flash('status', 'Addvise is temporarily unavailable. Please check back again later. We apologize for any inconvenience caused.');
                    return redirect('/');
                }

                $response = $fb->get('/oauth/access_token?grant_type=fb_exchange_token&client_id='
                    . $app_id . '&client_secret=' . $app_secret . '&fb_exchange_token=' . $token,
                    $token);
                $response_arr = $response->getDecodedBody();

                if (! array_key_exists('access_token', $response_arr)) {
                    Log::error('');
                } else {
                    $fb_user_token_long = $response_arr['access_token'];
                    $m_response = $fb->get('/me', $fb_user_token_long);
                    $m_response_arr = $m_response->getDecodedBody();
                    if (! array_key_exists('id', $m_response_arr)) {
                        Log::error('');
                    } else {
                        $fb_user_id = $m_response_arr['id'];
                    }
                    $user = User::where('fb_user_id', $fb_user_id)->first();
                    if (! $user) {
                        $new_user = new User;
                        $new_user->fb_user_id = $fb_user_id;
                        $new_user->save();
                    }
                    $token_row = Token::firstOrNew(['fb_user_id' => $fb_user_id]);
                    $token_row->fb_user_token_temp = $token;
                    $token_row->fb_user_token_long = $fb_user_token_long;
                    $token_row->fb_user_id = $fb_user_id;
                    $token_row->save();
                }

            } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                Log::error($e->getMessage());
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                Log::warn('Middleware IsConnectedToFacebook: FacebookResponseException');
                Log::warn($e->getMessage());
                $request->session()->flash('status', 'Your authorization code has expired. Time for a refresh!');
                return redirect('/');
            }
        }

        $request->attributes->add(['fb_user_id' => $fb_user_id, 'fb_user_token' => $fb_user_token_long]);
        return $next($request);
    }
}
