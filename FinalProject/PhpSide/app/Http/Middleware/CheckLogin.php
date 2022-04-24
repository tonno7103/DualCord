<?php
    namespace App\Http\Middleware;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Cache;
    use Closure;

    class CheckLogin{

        public array $data;
        public function __construct(){
            $this->data = json_decode(file_get_contents(storage_path() . "/configs.json"), true);
        }

        /**
         * Handle an incoming request.
         *
         * @param  Request  $request
         * @param  Closure  $next
         * @return mixed
         */
        public function handle(Request $request, Closure $next): mixed
        {
            $user = Auth::user();
            if(!$user){
                return redirect($this->data['address']. $this->data['phpPort'] . '/auth/login');
            }
            return $next($request);
        }
    }
?>
