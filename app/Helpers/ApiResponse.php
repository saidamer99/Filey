<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class ApiResponse
{
    const MSG_ADDED_SUCCESSFULLY = 'responses.msg_added_successfully';
    const MSG_UPDATED_SUCCESSFULLY = "responses.msg_updated_successfully";
    const MSG_DELETED_SUCCESSFULLY = "responses.msg_deleted_successfully";
    const MSG_NOT_ALLOWED = "responses.msg_not_allowed";
    const MSG_NOT_AUTHORIZED = "responses.msg_not_authorized";
    const MSG_NOT_AUTHENTICATED = "responses.msg_not_authenticated";
    const MSG_USER_NOT_ENABLED = "responses.msg_user_not_enabled";
    const MSG_NOT_FOUND = "responses.msg_not_found";
    const MSG_USER_NOT_FOUND = "responses.msg_user_not_found";
    const MSG_EMAIL_NOT_VERIFIED = "your email not verified";
    const MSG_WRONG_PASSWORD = "responses.msg_wrong_password";
    const MSG_SUCCESS = "responses.msg_success";
    const MSG_MIGRATED_SUCCESS = "responses.msg_migrated_success";
    const MSG_FAILED = "responses.msg_failed";
    const MSG_LOGIN_SUCCESSFULLY = "responses.msg_login_successfully";
    const MSG_LOGIN_FAILED = "responses.msg_login_failed";
    const MSG_LOGOUT_SUCCESSFULLY = "responses.msg_logout_successfully";
    const CONFLICT_REPO_NAMESPACE = "responses.msg_conflict_repo_namespace";
    const MSG_MODEL_NOT_FOUND = "responses.msg_model_not_found";
    const MSG_SQL_ERROR = "responses.msg_sql_error";
    const MSG_HTTP_NOT_FOUND = "responses.msg_http_not_found";
    const MSG_TOKEN_MISSING_FAILED = "responses.msg_token_missing";
    const MSG_MISSING_REQUIRED_VALUES = "responses.msg_missing_required_values";
    const MSG_UNIQUE = "responses.msg_unique";
    const MSG_MATCH_OLD_PASSWORD = "responses.msg_match_old_password";

    /**
     * return success responses with data and pagination if request has paginate request.
     *
     * @param array|null $data
     * @param string $message
     * @param int $code
     * @param array $pagination
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = '', $code = 200, $pagination = null)
    {
        $response_content = [
            'data' => $data,
            'message' => trans($message),
            'status' => $code == 200 || $code == 204 || $code == 201 || $code == 203,
            'pagination' => $pagination
        ];

        return response()->json($response_content, 200);
    }


    /**
     * return error response with message and status only.
     *
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = '', $code = 500)
    {
        $response_content = [
            'message' => trans($message),
            'status' => false,
        ];

        return response()->json($response_content, 200);
    }

    public static function getImages()
    {
        return collect(Storage::allFiles('public/images/login'))
            ->transform(fn ($image) => asset('storage/' . explode('/', $image, 2)[1]))
            ->random(3);
    }
}
