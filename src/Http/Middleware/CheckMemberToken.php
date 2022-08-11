<?php

namespace ZHK\Tool\Http\Middleware;

use App\Models\Member\Member;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Closure;

class CheckMemberToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next = null)
    {
        try {
            $token = JWTAuth::getToken();
            if (empty($token)) {
                return response()->json(['code' => 401, 'message' => '未登录']);
            }

            $sub = JWTAuth::setToken($token)->getPayload()->get('sub');
            if (empty($sub)) {
                return response()->json(['code' => 402, 'message' => '用户异常']);
            }
            $member = Member::find($sub->memberId);

            //如果想向控制器里传入用户信息，将数据添加到$request里面
            $request->attributes->add(['member' => json_encode($member->toArray())]); //添加参数
            //其他地方获取用户值
//            var_dump($request->attributes->get('member'));exit();
            if (!empty($next)) {
                return $next($request);
            }
        } catch (TokenExpiredException $e) {
            try {
                $token = JWTAuth::refresh();
                if ($token) {
                    return response()->json(['code' => 403, 'message' => '新token', 'token' => $token]);
                }
            } catch (JWTException $e) {
                return response()->json(['code' => 404, 'message' => 'token无效', 'token' => '']);
            }
        } catch (\TypeError $e) {
            return response()->json(['code' => 501, 'message' => 'token无效']);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'message' => 'token无效']);
        }
    }
}
