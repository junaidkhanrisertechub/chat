<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;
use Config;

use DB;
use Exception;

// use model here

use GuzzleHttp;
use GuzzleHttp\Client;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Response;
use URL;
use Validator;

use DateTime;
use DateInterval;

class LoginController extends Controller
{
	/**
	 * Function to authenticate user and login
	 *l
	 * @return \Illuminate\Http\Response
	 */

    public function showLoginForm(Request $request)
    {
        $data['title'] = 'Login | Allotin';
		$data['appenv'] = env('APP_ENV');
        return view('auth.login', compact('data'));
    }


    public function login(Request $request)
    {
        try {

            $arrInput        = $request->all();
            $validator = Validator::make($arrInput, [
                'email'  => 'required',
                'password' => 'required',
            ]);
			//'h-captcha-response' => 'required',
			// 'g-recaptcha-response' => 'required',
            // check for validation
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $userData = User::select('password')->where([['email', '=', $arrInput['email']]])->first();
            if (empty($userData)) {
                $intCode    = Response::HTTP_UNAUTHORIZED;
                $strStatus  = Response::$statusTexts[$intCode];
                $strMessage = 'Invalid User ID';
                toastr()->error($strMessage);
                return redirect()->back();
            } else {
                    // check user status
                    $arrWhere       = [['email', $arrInput['email']], ['user_status', 'Online']];

                    //changed column to bcrypt password :: Imran
                    $userDataActive = User::select('password')->where($arrWhere)->first();
                    if (empty($userDataActive)) {
                        $intCode    = Response::HTTP_UNAUTHORIZED;
                        $strStatus  = Response::$statusTexts[$intCode];
                        $strMessage = 'User is inactive,Please contact to admin';
                        //toastr()->error($strMessage);
						toastr()->error($strMessage, ['timeOut' => 2000]);
                        return redirect()->back();
                    }
                }


            //changed column to bcrypt password :: Imran
            $credentials = array('email' => $request->input('email'), 'password' => $arrInput['password']);
            //dd(Auth::attempt($credentials));
            //changed auth call & added extra else :: Imran
            if (Auth::attempt($credentials)) {
                $strMessage	= "Login successful.";
                toastr()->success($strMessage);
                return redirect()->intended('user/dashboard');
            }
            else{
                $strMessage    = "The user credentials were incorrect";
                //toastr()->error($strMessage);
				toastr()->error($strMessage, ['timeOut' => 2000]);
                return redirect()->back();
            }
        } catch (Exception $e) {
			$strMessage    = "The user credentials were incorrect";
            //toastr()->error($strMessage);
			toastr()->error($strMessage, ['timeOut' => 2000]);
            return redirect()->back();
        }
    }

    /**
	 * Function for Logout
	 */
	public function logout(Request $request)
	{
		$strStatus     = trans('user.error');
		$arrOutputData = [];
		try {
            if (empty(Auth::user())) {
                //toastr()->error('Something went wrong');
				toastr()->error('Something went wrong', ['timeOut' => 2000]);
                return redirect()->back();
            } else {

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                Auth::logout();

                toastr()->success('Logout Successfully');

                return redirect('/login');
            }
		} catch (Exception $e) {
            //toastr()->error('Something went wrong');
			toastr()->error('Something went wrong', ['timeOut' => 20000]);
            return redirect()->back();
        }
	}

	/**
	 * Function to verify the otp
	 *
	 */
	public function checkOtpStatus(Request $request) {
		$strMessage = trans('user.error');
		$arrOutputData = [];
		try {
			$arrInput = $request->all();
			$user= User::select('google2fa_status','id','email')->where('user_id','=',$arrInput['user_id'])->first();

			if(empty($user)){
                $strMessage = '';
				$intCode = Response::HTTP_NOT_FOUND;
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}else{
             	$id = $user->id;
			}
			// $projectSetting = ProjectSettingModel::select('otp_status')->first();
			// if (!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
				if($user->google2fa_status=='enable') {
					$arrOutputData['otp_type']   		= 'google2fa';
					$arrOutputData['email']   		= $user->email;
					$intCode = Response::HTTP_OK;
					$strStatus = Response::$statusTexts[$intCode];
					$strMessage = "2FA Enabled";
					return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
				} else{
					$strMessage = '';
					$arrOutputData['email']   		= $user->email;
					$intCode = Response::HTTP_NOT_FOUND;
					$strStatus = Response::$statusTexts[$intCode];
					return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
				}

			/*}else{
				$strMessage = '';
				$intCode = Response::HTTP_NOT_FOUND;
				$strStatus = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}*/

		} catch (Exception $e) {dd($e);
			//return ['response' => $e->getMessage()];
			$intCode = Response::HTTP_BAD_REQUEST;
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		}
	}

	/**
	 * Function Only User
	 */
	public function getOnlyUser(Request $request)
	{
		$user = Auth::user();
		if (!empty($user)) {
			$users_list = User::select('id', 'user_id', 'fullname');

			$users_list = $users_list->skip(0)->take(20)->get();

			if (!empty($users_list)) {
				return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'Data found', $users_list);
			} else {
				return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Data not found', '');
			}
		} else {
			return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'User Unaunthenticated', '');
		}
	}
	/**
	 * Function to verify the otp
	 *
	 */
	public function checkOtp(Request $request)
	{
		$strMessage    = trans('user.error');
		$arrOutputData = [];
		try {
			$arrInput = $request->all();
			$otp      = trim($arrInput['otp']);
			$user     = Auth::user();

			$id            = $user->id;
			$checotpstatus = OtpModel::where([
				['id', '=', $id],
				['otp', '=', md5($otp)]
			])->orderBy('otp_id', 'desc')->first();
			// check otp status 1 - already used otp
			if (empty($checotpstatus)) {
				$strMessage = 'Invalid otp for token';
				$intCode    = Response::HTTP_BAD_REQUEST;
				$strStatus  = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			if ($checotpstatus->otp_status == 1) {
				// otp already veriied
				$strMessage = 'Otp already verified';
				$intCode    = Response::HTTP_BAD_REQUEST;
				$strStatus  = Response::$statusTexts[$intCode];
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}

			// make otp verify
			secureLogindata($user->user_id, $user->password, 'Login successfully');
			$updateData               = array();
			$updateData['otp_status'] = 1; //1 -verify otp
			$updateData['out_time']   = date('Y-m-d H:i:s');
			$updateOtpSta             = OtpModel::where('id', $id)->update($updateData);
			if (!empty($updateOtpSta)) {
				// ==============activity notification==========
				$date                  = \Carbon\Carbon::now();
				$today                 = $date->toDateTimeString();
				$actdata               = array();
				$actdata['id']         = $id;
				$actdata['message']    = 'Login successfully with IP address ( ' . $request->ip() . ' ) at time (' . $today . ' ) ';
				$actdata['status']     = 1;
				$actdata['entry_time'] = $this->today;
				$actDta                = ActivitynotificationModel::create($actdata);
			} // end of else
			$intCode    = Response::HTTP_OK;
			$strStatus  = Response::$statusTexts[$intCode];
			$strMessage = "Otp Verified.Login successfully";
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		} catch (Exception $e) {
			//return ['response' => $e->getMessage()];
			$intCode   = Response::HTTP_BAD_REQUEST;
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		}
	}

	/**
	 * Function to verify the mobile no
	 *
	 * @param $request : HTTP Request object
	 *
	 */
	public function verifyMobile(Request $request)
	{
		$intCode   = Response::HTTP_BAD_REQUEST;
		$strStatus = Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput      = $request->all();
			$user          = Auth::user();
			$validator     = Validator::make($arrInput, [
				'otp' => 'required',
			]);
			## check for validation
			if ($validator->fails()) {
				$strMessage = trans('user.error');
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			if ($user->status == 'Active' || $user->mobileverify_status == '1') {
				$strMessage = "Mobile already verified";
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}

			// user in inactive then verify mobile

			$otp = $arrInput['otp'];
			if ($otp != $user->mobile_otp) {
				secureLogindata($user->user_id, $user->password, 'Invalid verification code', $user->mobile);
				$strMessage = 'Invalid verification code';
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			$arrOutputData['mobileverification'] = 'FALSE';
			$arrOutputData['mailverification']   = 'FALSE';
			$arrOutputData['google2faauth']      = 'FALSE';
			$arrOutputData['mailotp']            = 'FALSE';
			$user->status                        = 'Active';
			$user->mobileverify_status           = '1';
			$user->save();
			secureLogindata($user->user_id, $user->password, 'Mobile verified successfully');
			$strMessage = "Mobile verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus  = Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode    = Response::HTTP_INTERNAL_SERVER_ERROR;
			$strStatus  = Response::$statusTexts[$intCode];
			$strMessage = trans('user.error');
		}
		return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
	}

	/**
	 * Function to send the OTP
	 *
	 * @param $user 	: User object
	 *
	 * @param $otpMode 	: OTP Mode(mobile/mail)
	 */
	public function sendOtp($users, $otpMode)
	{
		$arrOutputData                     = [];
		$arrOutputData['mailverification'] = $arrOutputData['mobileverification'] = 'FALSE';
		$arrOutputData['google2faauth']    = $arrOutputData['mailotp']    = $arrOutputData['otpmode']    = 'FALSE';
		DB::beginTransaction();
		try {
			$otpInterval = Config::get('constants.settings.OTP_interval');

			$checotpstatus = OtpModel::where([['id', '=', $users->id],])->orderBy('entry_time', 'desc')->first();
			if (!empty($checotpstatus)) {
				$entry_time   = $checotpstatus->entry_time;
				$out_time     = $checotpstatus->out_time;
				$checkmin     = date('Y-m-d H:i:s', strtotime($otpInterval, strtotime($entry_time)));
				$current_time = date('Y-m-d H:i:s');
			}

			if (false/*!empty($checotpstatus) && $entry_time!='' && strtotime($checkmin)>=strtotime($current_time) && $checotpstatus->otp_status!='1'*/) {
				$updateData               = array();
				$updateData['otp_status'] = 0;

				$updateOtpSta = OtpModel::where('id', $users->id)->update($updateData);
				$intCode      = Response::HTTP_BAD_REQUEST;
				$strStatus    = Response::$statusTexts[$intCode];
				$strMessage   = 'OTP already sent to your mobile no';
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			} else {
				$random = rand(100000, 999999);
				// send otp as 123456 if webservice hit from local
				if ($_SERVER['REMOTE_ADDR'] == '192.168.21.173') {
					$random = "123456";
				}

				$insertotp               = array();
				$insertotp['id']         = $users->id;
				$insertotp['ip_address'] = trim($_SERVER['REMOTE_ADDR']);
				$insertotp['otp']        = md5($random);
				$insertotp['otp_status'] = 0;
				$insertotp['type']       = $otpMode;
				if ($otpMode == 'mobile') {
					$msg = $random . ' is your verification code';
					sendMessage($users, $msg);
				} else if ($otpMode == 'mail' || $otpMode == 'email') {
					$arrEmailData['email']    = $users->email;
					$arrEmailData['subject']  = 'Otp';
					$arrEmailData['otp']      = $random;
					$arrEmailData['template'] = 'email.otp';
					$arrEmailData['fullname'] = $users->fullname;
					//dD($arrEmailData);
					sendEmail($arrEmailData);
				} else if ($otpMode == 'both') {
					// send email and message from here
					$msg = $random . ' is your verification code';
					sendMessage($users, $msg);
					$arrEmailData['email']    = $users->email;
					$arrEmailData['subject']  = 'Otp';
					$arrEmailData['otp']      = $random;
					$arrEmailData['template'] = 'email.otp';
					$arrEmailData['fullname'] = $users->fullname;
					sendEmail($arrEmailData);
				}

				$sendotp       = OtpModel::create($insertotp);
				$arrOutputData = array();
				// $arrOutputData['id']   = $users->id;
				$arrOutputData['mailverification']   = 'FALSE';
				$arrOutputData['google2faauth']      = 'FALSE';
				$arrOutputData['mailotp']            = 'TRUE';
				$arrOutputData['mobileverification'] = 'FALSE';
				//$arrOutputData['otpmode']   = ($otpMode == 'mobile') ? 'TRUE' :'FALSE';
				$arrOutputData['otpmode']    = $otpMode;
				$arrOutputData['master_pwd'] = 'FALSE';
				// for now overrite with false;
				// $arrOutputData['otpmode']   = 'FALSE';
				$mask_mobile             = maskMobileNumber($users->mobile);
				$mask_email              = maskEmail($users->email);
				$arrOutputData['email']  = $mask_email;
				$arrOutputData['mobile'] = $mask_mobile;
				//$arrOutputData['otp']	=	$random;
			}
		} catch (PDOException $e) {
			DB::rollBack();
		} catch (Exception $e) {
			DB::rollBack();
		}
		DB::commit();
		return $arrOutputData;
	}

	/**
	 * Function to verify the mobile no
	 *
	 * @param $request : HTTP Request object
	 *
	 */
	public function verifyEmail(Request $request)
	{
		$intCode   = Response::HTTP_BAD_REQUEST;
		$strStatus = Response::$statusTexts[$intCode];
		try {
			$arrOutputData = [];
			$arrInput      = $request->all();
			$user          = Auth::user();
			$validator     = Validator::make($arrInput, [
				'verifytoken' => 'required',
			]);
			## check for validation
			if ($validator->fails()) {
				$strMessage = trans('user.error');
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			if ($user->status == 'Active' || $user->verifyaccountstatus == '1') {
				$strMessage = "Email already verified";
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}

			// user in inactive then verify mobile

			$verifytoken = $arrInput['verifytoken'];
			if ($verifytoken != $user->verifytoken) {
				secureLogindata($user->user_id, $user->password, 'Invalid token', $user->mobile);
				$strMessage = 'Invalid token';
				return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
			}
			$arrOutputData['mobileverification'] = 'FALSE';
			$arrOutputData['mailverification']   = 'FALSE';
			$arrOutputData['google2faauth']      = 'FALSE';
			$arrOutputData['mailotp']            = 'FALSE';
			$user->status                        = 'Active';
			$user->verifyaccountstatus           = '1';
			$use->status                         = 'Active';
			$user->save();
			$dashdata     = new DashboardModel;
			$dashdata->id = $user->id;
			$dashdata->save();

			secureLogindata($user->user_id, $user->password, 'Email verified successfully');
			$strMessage = "Congratulations your email id verified successfully";
			$intCode    = Response::HTTP_OK;
			$strStatus  = Response::$statusTexts[$intCode];
		} catch (Exception $e) {
			$intCode    = Response::HTTP_INTERNAL_SERVER_ERROR;
			$strStatus  = Response::$statusTexts[$intCode];
			$strMessage = trans('user.error');
		}
		return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
	}

	/**
	 * Function to resend the otp
	 *
	 * @param $request : HTTP Request
	 */
	public function resendOtp(Request $request)
	{
		$arrOutputData = [];
		try {
			$arrOutputData['mobileverification'] = 'FALSE';
			$arrOutputData['mailverification']   = 'FALSE';
			$arrOutputData['google2faauth']      = 'FALSE';
			$arrOutputData['mailotp']            = 'FALSE';
			$arrOutputData['otpmode']            = 'FALSE';
			$arrOutputData['master_pwd']         = 'FALSE';
			$strMessage                          = "Error in resending otp";
			$arrInput                            = $request->all();
			$projectSetting                      = ProjectSettingModel::first();
			$user                                = Auth::user();
			if (!empty($projectSetting) && ($projectSetting->otp_status == 'on')) {
				// if google 2 fa is enable then dont issue OTP
				if ($user->google2fa_status == 'enable') {
					$arrOutputData['google2faauth'] = 'TRUE';
				} else {
					// issue token
					$otpMode = '';
					if ($user->type != 'Admin') {
						if (isset($arrInput['otp']) && $arrInput['otp'] == 'mail') {
							$otpMode = 'email';
						}
						if (isset($arrInput['otp']) && $arrInput['otp'] == 'mobile') {
							$otpMode = 'email';
						}
					} else {
						$otpMode = 'mobile';
					}
					if ($otpMode != '') {
						$arrOutputData = $this->sendOtp($user, $otpMode);
						$strMessage    = "Otp resent";
					}
				}
			}
			$intCode   = Response::HTTP_OK;
			$strStatus = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		} catch (Exception $e) {
			$strMessage = "Something went wrong";
			$intCode    = Response::HTTP_UNAUTHORIZED;
			$strStatus  = Response::$statusTexts[$intCode];
			return sendResponse($intCode, $strStatus, $strMessage, $arrOutputData);
		}
	}
    public function checkUserExist(Request $request) {
        try {
            $arrInput = $request->user_id;
            //validate the info, create rules for the inputs
            $rules = array(
                'user_id' => 'required',

            );
            // dd($arrInput);


            // run the validation rules on the inputs from the form
            $validator = Validator::make($request->all(), $rules);
            // if the validator fails, redirect back to the form
            if ($validator->fails()) {
                $message    = $validator->errors();
                $arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
                $arrCode    = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Sponsor ID required';
                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            } else {
                //check wether user exist or not by user_id
                $checkUserExist = User::where('user_id', trim($request->user_id))->where('type', '!=', 'Admin')->select('id', 'user_id', 'fullname', 'remember_token')->first();

                if (!empty($checkUserExist)) {

                    $arrObject['id']             = $checkUserExist->id;
                    $arrObject['user_id']        = $checkUserExist->user_id;
                    $arrObject['fullname']       = $checkUserExist->fullname;
                    $arrObject['remember_token'] = $checkUserExist->remember_token;

                    $arrStatus  = Response::HTTP_OK;
                    $arrCode    = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'User available';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, $arrObject);
                } else {
                    $arrStatus  = Response::HTTP_NOT_FOUND;
                    $arrCode    = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Invalid user';
                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                }
            }
        } catch (Exception $e) {
            $arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode    = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Something went wrong,Please try again';
            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
        }
    }
    public function getUserId(Request $request) {
        $uid    = $request->uid;

        $userid = User::where('user_id', $uid)->pluck('user_id')->first();
        if (!empty($userid)) {
            return sendresponse($this->statuscode[200]['code'], $this->statuscode[200]['status'], 'User found successfully!', $userid);
        } else {
            return sendresponse($this->statuscode[404]['code'], $this->statuscode[404]['status'], 'Invalid user', '');
        }
    }
    public function register(Request $request) {


        $arrInput  = $request->all();


        $adm_id = User::select('id')->where('type', '=', 'Admin')->get();
        $ref_id = User::select('id')->where('user_id', '=', $request->ref_user_id)->get();
        if ($adm_id == $ref_id) {
            $refCount = User::where('ref_user_id', '=', $adm_id[0]->id)->count();
            if ($refCount >= 1) {
                $arrStatus  = Response::HTTP_NOT_FOUND;
                $arrCode    = Response::$statusTexts[$arrStatus];
                $arrMessage = 'Cannot register with this Referral ID!';
//                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                //toastr()->error($arrMessage);
				toastr()->error($arrMessage, ['timeOut' => 2000]);
                return redirect()->back()->withInput();
            }
        }
        try {


            $projectSettings = ProjectSettings::where('status', 1)
                ->select('registration_status', 'char_fromate', 'user_id_Int_fromat', 'registration_msg')	->first();

            $int  = $projectSettings->user_id_Int_fromat;
            $char = $projectSettings->char_fromate;

			$validator = Validator::make($arrInput, [
                'captcha' => 'required',
            ],
                [
                    'captcha.required' => 'Captcha field is required',
//                    'captcha.captcha' => 'Captcha does not match'
                ]
            );
			//'h-captcha-response' => 'required',
			//'g-recaptcha-response' => 'required',
            // check for validation
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            $arrValidation = User::registrationValidationRules();
            $validator     = checkvalidation($request->all(), $arrValidation['arrRules'], $arrValidation['arrMessage']);


            $result=isPasswordValid($request->password);


            if($result['status']==false)
            {
                $arrStatus  = Response::HTTP_NOT_FOUND;
                $arrCode    = Response::$statusTexts[$arrStatus];
                $arrMessage = $result['message'];
//                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                //toastr()->error($arrMessage);
				toastr()->error($arrMessage, ['timeOut' => 2000]);
                return redirect()->back()->withInput();
            }

            if ($projectSettings->registration_status == "off") {
                $intCode    = Response::HTTP_UNAUTHORIZED;
                $strStatus  = Response::$statusTexts[$intCode];
                $strMessage = $projectSettings->registration_msg;
				$arrMessage = "Login Off";
//                return sendResponse($intCode, $strStatus, $strMessage, '');
                toastr()->error($arrMessage);
                return redirect()->back()->withInput();
            }


            if (!empty($validator)) {

                $arrStatus  = Response::HTTP_NOT_FOUND;
                $arrCode    = Response::$statusTexts[$arrStatus];
                $arrMessage = $validator;
//                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                toastr()->error($arrMessage);
                return redirect()->back()->withInput();
            }

            $getuser = $this->checkSpecificUserData(['user_id' => $request->Input('user_id'), 'status' => 'Active']);

            if (empty($getuser)) {


                $refUserExist = User::select('user_id')->where([['user_id', '=', $request->Input('ref_user_id')], ['status', '=', 'Active']])->count();

                if ($refUserExist > 0) {
                    $registation_plan = ProjectSettings::where([['status', '=', 1]])->pluck('registation_plan')->first();

                    // if binary plan is on t
                    //echo $registation_plan;
                    if ($registation_plan == 'binary' && $request->Input('position') != 0) {
                        return $this->binaryPlan($request);
                    } else if ($registation_plan == 'level') {
                        // if level plan on
                        return $this->levelPlan($request);
                    } else {
                        $arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
                        $arrCode    = Response::$statusTexts[$arrStatus];
                        $arrMessage = 'Something went wrong,Please try again';
//                        return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                        toastr()->error($arrMessage);
                        return redirect()->back()->withInput();
                    }
                } else {

                    $arrStatus  = Response::HTTP_NOT_FOUND;
                    $arrCode    = Response::$statusTexts[$arrStatus];
                    $arrMessage = 'Sponser not exist';
//                    return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                    toastr()->error($arrMessage);
                    return redirect()->back()->withInput();
                }

            } else {

                $arrStatus  = Response::HTTP_CONFLICT;
                $arrCode    = Response::$statusTexts[$arrStatus];
                $arrMessage = 'User already registered exist';
//                return sendResponse($arrStatus, $arrCode, $arrMessage, '');
                toastr()->error($arrMessage);
                return redirect()->back()->withInput();
            }
        } catch (Exception $e) {
            $arrStatus  = Response::HTTP_INTERNAL_SERVER_ERROR;
            $arrCode    = Response::$statusTexts[$arrStatus];
            $arrMessage = 'Something went wrong,Please try again';
//            return sendResponse($arrStatus, $arrCode, $arrMessage, '');
            toastr()->error($arrMessage);
            return redirect()->back()->withInput();
        }
    }

}
