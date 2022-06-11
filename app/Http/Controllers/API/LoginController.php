<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\OtpVerification;
use \Carbon\Carbon;



class LoginController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [            
            'email' => 'required|email',
            'password' => 'required',            
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Given Data are incorrect']);
        } 
    }

    public function logout(){   
        if (auth()->user()->tokens()->delete()) {
			return $this->sendResponse('success', 'User logout successfully.');

		} else {
			return $this->sendError('Error', ['error' => 'User logout error.']);

		}
    }

    public function forgotPassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());                   
        }

        $user = User::where(['email' => $request->email])->first();
        if (empty($user)) {
            return $this->sendError('User Not Found');
        }

        $otp = random_int(1000, 9999);
        OtpVerification::where('email', $input['email'])->delete();
        $OtpVerification = new OtpVerification;
        $OtpVerification->otp = $otp;
        $OtpVerification->user_id = $user->id;
        $OtpVerification->email = $input['email'];
        $OtpVerification->is_used = 0;
        $OtpVerification->created_at = Carbon::now();
        $OtpVerification->updated_at = Carbon::now();
        if ($OtpVerification->save()) {
            $success_data['email'] = $OtpVerification->email;
            $success_data['otp'] = $OtpVerification->otp;                        
            return $this->sendResponse($success_data, 'Otp Sent.');
        } else {

            return $this->sendError('OTP NOT Generated.');
        }
    }

    public function forgotPasswordVerifyOtp(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|email',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());                               
        }

        $getOTP = OtpVerification::where('email', $request->email)->first();
        if (isset($getOTP->otp)) {
            if ($getOTP->otp == $request->otp) {
                $user = User::where(['email' => $getOTP->email])->first();
                if (empty($user)) {
                    return $this->sendError('User Not Found');
                }
                $success_data['id'] = $user->id;                
                $success_data['email'] = $user->email;                
                OtpVerification::where('email', $user->email)->delete();
                return $this->sendResponse($success_data, 'OTP Verified successfully.');
            } else {
                return $this->sendError('OTP Not Found');
            }
        } else {
            return $this->sendError('Need to resend OTP');
        }
    }

    public function resetPassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|gt:0',
            'password' => 'required',            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());                                          
        }

        $editdata = array('password' => bcrypt($input['password']));
        $res = User::where('id', $input['id'])->update($editdata);
        if ($res) {
            $user = User::find($input['id']);
            $success_data['name'] = $user->name;
            $success_data['id'] = $user->id;
            $success_data['email'] = $user->email;            
            return $this->sendResponse($success_data, 'User Password Updated successfully.');
        }
    }

  

}
