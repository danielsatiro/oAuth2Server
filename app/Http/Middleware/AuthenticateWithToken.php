<?php 

namespace App\Http\Middleware;

use Closure;

class AuthenticateWithToken {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$data = count(\Input::all()) > 0 ? \Input::all() : \Input::json()->all();
 
		if (!isset($data['token'])) {
			$response                 = array();
			$response['return_code']  = 401;
			$response['messages']     = 'Não autorizado!';//Lang::get('validation.custom.hash_auth');

			return \Response::json($response, $response['return_code']);
		}

		//decrypt token
		$token = \Crypt::decrypt($data['token']);
		$data['id_users'] = $token['id_users'];
		//set Input::all parameters with id_users decrypted
		\Input::merge($data);

		return $next($request);
	}

}
