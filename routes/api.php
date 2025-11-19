<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileCtrl;
use App\Http\Controllers\SampleCtrl;
use App\Http\Controllers\ExitInterviewctrl;
use App\Http\Controllers\Manage\DtrController;
use App\Http\Controllers\Leave\LeaveCtrl;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\Dtr\DtrCtrl;
use App\Http\Controllers\Event\EventCtrl;
use App\Http\Controllers\Auth\PwResetCtrl;
use App\Http\Controllers\PdsCtrl;
use App\Http\Controllers\PassSlipCtrl;
use App\Http\Controllers\Coc\CocApplicationController;
use App\Http\Controllers\BestCan\BestCanController;
use App\Http\Controllers\AttendanceCtrl;
use Illuminate\Support\Facades\Auth as FacadesAuth;

Route::group(['prefix' => 'auth'], function () {
  Route::post('lcs/login', [AuthController::class, 'lcslogin']);
  Route::post('password/email', [PwResetCtrl::class, 'sendResetLink']);
  Route::post('password/reset', [PwResetCtrl::class, 'resetPassword']);
  Route::post('login', [AuthController::class, 'login']);
  Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('validate-token', [AuthController::class, 'validateToken']);
    Route::get('users', [AuthController::class, 'users']);
     Route::post('users', [AuthController::class, 'storeUser']);     // create
    Route::put('users/{user}', [AuthController::class, 'updateUser']); // update
    Route::delete('users/{user}', [AuthController::class, 'destroyUser']); // delete
    // Route::get('dtr', [DtrController::class, 'dtr']);
  });
});


Route::group(['prefix' => 'manage'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('dtr', [DtrController::class, 'dtr']);
    // Route::get('dtr/user/{id}', [DtrController::class, 'getEmployee']);
    // Route::post('register', [DtrController::class, 'register']);
    // Route::get('employee', [DtrController::class, 'employees']);
    // Route::post('view/dtr', [DtrController::class, 'dtrView']);
    // Route::get('generate-dtr', [DtrController::class, 'generateDtr']);
  });
});

//Employees Portal
// Route::group(['prefix' => 'employee'], function () {
//   Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('me', [ProfileCtrl::class, 'me']);
//     Route::get('profile-pic', [ProfileCtrl::class, 'profile']);
//     Route::post('store/profile-pic', [ProfileCtrl::class, 'storeProfilePic']);
//     Route::post('store/profile', [ProfileCtrl::class, 'storeProfile']);
//     Route::post('store/security', [ProfileCtrl::class, 'storeSecurity']);
//     Route::post('store/signature', [ProfileCtrl::class, 'storeSignature']);
//     Route::get('fetch/signature', [ProfileCtrl::class, 'fetchSignature']);
//     Route::post('add/leave', [LeaveCtrl::class, 'index']);
//     Route::get('dtr/{id}', [DtrCtrl::class, 'index']);
//     Route::get('dtrv2', [DtrCtrl::class, 'dtrv2']);
//     Route::get('dtrv2/device-status', [DtrCtrl::class, 'deviceStatus']);
//     Route::post('attendance/{id}', [ProfileCtrl::class, 'attendance']);
//     Route::get('dashboard', [ProfileCtrl::class, 'dashboard']);
//     Route::get('daylog', [DtrCtrl::class, 'daylog']);
//     Route::get('events-activities', [EventCtrl::class, 'index']);
//     Route::get('events-activities/qr/{id}', [EventCtrl::class, 'generateEventQr']);
//     Route::post('store/events-activities', [EventCtrl::class, 'store']);
//     Route::get('events-activities/view/{id}', [EventCtrl::class, 'viewEventDetails']);
//     Route::get('events-activities/export/evaluation/{id}', [EventCtrl::class, 'exportEval']);
//     Route::get('events-activities/export/cssform2/{id}', [EventCtrl::class, 'exportCss2']);
//     Route::get('events-activities/export/cssform3/{id}', [EventCtrl::class, 'exportCss3']);
//     Route::get('events-activities/export/csm/{id}', [EventCtrl::class, 'exportCsm']);
//     Route::get('events-activities/form/evaluation/{id}', [EventCtrl::class, 'formEval']);
//     Route::get('events-activities/form/cssform2/{id}', [EventCtrl::class, 'formCss2']);
//     Route::get('events-activities/form/cssform3/{id}', [EventCtrl::class, 'formCss3']);
//     Route::get('events-activities/form/csm/{id}', [EventCtrl::class, 'formCsm']);
//     Route::get('pds/basic', [PdsCtrl::class, 'basic']);
//     Route::post('store/pds/basic', [PdsCtrl::class, 'basicStore']);
//     Route::get('pds/address', [PdsCtrl::class, 'address']);
//     Route::post('store/pds/address', [PdsCtrl::class, 'addressStore']);
//     Route::get('pds/identification', [PdsCtrl::class, 'identification']);
//     Route::post('store/pds/identification', [PdsCtrl::class, 'identificationStore']);
//     Route::get('pds/family', [PdsCtrl::class, 'family']);
//     Route::post('store/pds/family', [PdsCtrl::class, 'familyStore']);
//     Route::get('pds/education', [PdsCtrl::class, 'education']);
//     Route::post('delete/pds/education/{id}', [PdsCtrl::class, 'educationDelete']);
//     Route::post('store/pds/education', [PdsCtrl::class, 'educationStore']);
//     Route::get('pds/eligibility', [PdsCtrl::class, 'eligibility']);
//     Route::post('store/pds/eligibility', [PdsCtrl::class, 'eligibilityStore']);
//     Route::post('delete/pds/eligibility/{id}', [PdsCtrl::class, 'eligibilityDelete']);
//     Route::get('pds/work', [PdsCtrl::class, 'work']);
//     Route::post('store/pds/work', [PdsCtrl::class, 'workStore']);
//     Route::post('delete/pds/work/{id}', [PdsCtrl::class, 'workDelete']);
//     Route::get('pds/voluntary', [PdsCtrl::class, 'voluntary']);
//     Route::post('store/pds/voluntary', [PdsCtrl::class, 'voluntaryStore']);
//     Route::post('delete/pds/voluntary/{id}', [PdsCtrl::class, 'voluntaryDelete']);
//     Route::get('pds/training', [PdsCtrl::class, 'training']);
//     Route::post('store/pds/training', [PdsCtrl::class, 'trainingStore']);
//     Route::post('delete/pds/training/{id}', [PdsCtrl::class, 'trainingDelete']);
//     Route::get('pds/other', [PdsCtrl::class, 'other']);
//     Route::post('store/pds/other', [PdsCtrl::class, 'otherStore']);
//     Route::get('pds/generate', [PdsCtrl::class, 'generatePDS']);
//     Route::get('pds/generate/page2', [PdsCtrl::class, 'generatePDS2']);
//     Route::get('pds/generate/page3', [PdsCtrl::class, 'generatePDS3']);
//     Route::get('pds/generate/page4', [PdsCtrl::class, 'generatePDS4']);
//     //Pass Slip
//     Route::apiResource('pass-slips', PassSlipCtrl::class);
//     Route::get('pass-slip/approval', [PassSlipCtrl::class, 'getApprove']);
//     Route::post('pass-slip/approve/{id}', [PassSlipCtrl::class, 'approve']);
//     Route::post('pass-slip/scan/{id}', [PassSlipCtrl::class, 'scan']);
//     Route::get('pass-slip/generate/{id}', [PassSlipCtrl::class, 'generate']);
//     Route::post('pass-slips-active/{id}', [PassSlipCtrl::class, 'scanPassSlip']);
//   });
// });

// Route::get('dtr', [DtrController::class, 'dtr']);
// //For Employee
// Route::group(['prefix' => 'users'], function () {
//   Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::get('dtr', [DtrController::class, 'dtrView']);
//   });
// });
// //For testing
// Route::get('attendance', [SampleCtrl::class, 'attendance']);

//Route::get('dtr', [AuthController::class, 'dtr']);

//Route Leaves
Route::group(['prefix' => 'leaves'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/leavetype', [LeaveApplicationController::class, 'get_leave_type']);
    // Route::get('/leavedetails', [LeaveApplicationController::class, 'get_leave_details']);
    // Route::post('/add', [LeaveApplicationController::class, 'store']);
    // Route::get('/leaveretrive/{id}', [LeaveApplicationController::class, 'edit']);
    // Route::post('/update', [LeaveApplicationController::class, 'UpdateLeave']);
    // Route::get('/leaveuser/{userId}', [LeaveApplicationController::class, 'getUserleaves']);

  });
});

//Route COC
Route::group(['prefix' => 'cocs'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::post('/create', [CocApplicationController::class, 'create']);
    // Route::post('/attachments/upload', [CocApplicationController::class, 'uploadAttachments']);
    // Route::post('/update', [CocApplicationController::class, 'updateData']);
    // Route::get('/document_type', [CocApplicationController::class, 'get_document']);
    // Route::get('/cocuser/{userId}', [CocApplicationController::class, 'getUserCOC']);
    // Route::get('/cocretrive/{app_number}', [CocApplicationController::class, 'edit']);
    // Route::get('/retrieve/{app_number}', [CocApplicationController::class, 'getCOCWithAttachments']);
  });
});

//Route BestCan

Route::group(['prefix' => 'bestcans'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/division', [BestCanController::class, 'bestcandiv']);
    // Route::get('/sections/{divisionid}', [BestCanController::class, 'getSectionByDivision']);
    // Route::get('/employees', [BestCanController::class, 'getUserEmp']);
    // Route::get('/position/{emp_id}', [BestCanController::class, 'getPosition']);
    // Route::post('save-update-best-can', [BestCanController::class, 'aeBestCan']);
    // Route::get('/get-best-can/{id}', [BestCanController::class, 'getBestcan']);
    // Route::match(['GET', 'POST'], 'fetch-nominees', [BestCanController::class, 'fetchNominees']);
    // Route::get('/get-position-emp', [BestCanController::class, 'getNominee']);
  });
});
//Route ExitInterviews
Route::group(['prefix' => 'exitinterviews'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/exit-interviews', [ExitInterviewctrl::class, 'index']);
    // Route::post('/exit-interviews', [ExitInterviewctrl::class, 'store']);
  });
});

Route::group(['prefix' => 'attendance'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::get('/view-attendance-user', [AttendanceCtrl::class, 'attendanceUser']);
  });
});
Route::group(['middleware' => 'auth:sanctum'], function () {
Route::get('/auth/status', function () {
    return response()->json([
        'logged_in' => FacadesAuth::check(),
        'user' => FacadesAuth::user(),
    ]);
});
 });
