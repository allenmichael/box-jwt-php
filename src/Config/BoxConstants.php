<?php

namespace Box\Config;
abstract class BoxConstants
{
    const BOX_API_HOST_URI_STRING   = 'https://app.box.com/api/';
    const BOX_API_URI_STRING        = 'https://api.box.com/2.0/';
    const BOX_API_JWT_STRING        = 'https://api.box.com/';
    const BOX_UPLOAD_API_URI_STRING = 'https://upload.box.com/api/2.0/';

    const AUTH_CODE_STRING           = 'oauth2/authorize';
    const AUTH_TOKEN_ENDPOINT_STRING = 'oauth2/token';
    const REVOKE_ENDPOINT_STRING     = 'oauth2/revoke';

    const FOLDERS_STRING                      = 'folders/';
    const GROUPS_STRING                       = 'groups/';
    const GROUP_MEMBERSHIP_STRING             = 'group_memberships/';
    const FILES_STRING                        = 'files/';
    const FILES_UPLOAD_STRING                 = 'files/content';
    const FILES_NEW_VERSION_STRING            = 'files/%s/content';
    const COMMENTS_STRING                     = 'comments/';
    const SEARCH_STRING                       = 'search';
    const USER_STRING                         = 'users/';
    const USER_ME_STRING                      = 'me';
    const COLLABORATIONS_STRING               = 'collaborations/';
    const RETENTION_POLICIES_STRING           = 'retention_policies/';
    const RETENTION_POLICY_ASSIGNMENTS_STRING = 'retention_policy_assignments/';
    const FILE_VERSION_RETENTIONS_STRING      = 'file_version_retentions';

    const AUTH_CODE_JWT_ENDPOINT_STRING     = self::BOX_API_JWT_STRING . self::AUTH_TOKEN_ENDPOINT_STRING;
    const AUTH_CODE_ENDPOINT_STRING         = self::BOX_API_HOST_URI_STRING . self::AUTH_CODE_STRING;
    const FOLDERS_ENDPOINT_STRING           = self::BOX_API_URI_STRING . self::FOLDERS_STRING;
    const GROUPS_ENDPOINT_STRING            = self::BOX_API_URI_STRING . self::GROUPS_STRING;
    const GROUP_MEMBERSHIP_ENDPOINT_STRING  = self::BOX_API_URI_STRING . self::GROUP_MEMBERSHIP_STRING;
    const FILES_ENDPOINT_STRING             = self::BOX_API_URI_STRING . self::FILES_STRING;
    const FILES_UPLOAD_ENDPOINT_STRING      = self::BOX_UPLOAD_API_URI_STRING . self::FILES_UPLOAD_STRING;
    const FILES_NEW_VERSION_ENDPOINT_STRING = self::BOX_UPLOAD_API_URI_STRING . self::FILES_NEW_VERSION_STRING;
    const COMMENTS_ENDPOINT_STRING          = self::BOX_API_URI_STRING . self::COMMENTS_STRING;
    const SEARCH_ENDPOINT_STRING            = self::BOX_API_URI_STRING . self::SEARCH_STRING;
    const USER_ENDPOINT_STRING              = self::BOX_API_URI_STRING . self::USER_STRING;
    const USER_ME_ENDPOINT_STRING           = self::USER_ENDPOINT_STRING . self::USER_ME_STRING;
    const COLLABORATIONS_ENDPOINT_STRING    = self::BOX_API_URI_STRING . self::COLLABORATIONS_STRING;

    const ITEMS_PATH_STRING                            = '%s/items';
    const VERSIONS_PATH_STRING                         = '%s/versions';
    const COPY_PATH_STRING                             = '%s/copy';
    const COMMENTS_PATH_STRING                         = '%s/comments';
    const THUMBNAIL_PNG_PATH_STRING                    = '%s/thumbnail.png';
    const THUMBNAIL_JPG_PATH_STRING                    = '%s/thumbnail.jpg';
    const TRASH_PATH_STRING                            = '%s/trash';
    const DISCUSSIONS_PATH_STRING                      = '%s/discussions';
    const COLLABORATIONS_PATH_STRING                   = '%s/collaborations';
    const TRASH_ITEMS_PATH_STRING                      = '%s/items';
    const TRASH_FOLDER_PATH_STRING                     = '%s/trash';
    const GROUP_MEMBERSHIP_PATH_STRING                 = '%s/memberships';
    const CONTENT_PATH_STRING                          = '%s/content';
    const RETENTION_POLICY_ASSIGNMENTS_ENDPOINT_STRING = '%s/assignments';

    const TYPE_FILE                        = 'file';
    const TYPE_FOLDER                      = 'folder';
    const TYPE_COMMENT                     = 'comment';
    const TYPE_WEB_LINK                    = 'web_link';
    const TYPE_RETENTION_POLICY            = 'retention_policy';
    const TYPE_RETENTION_POLICY_ASSIGNMENT = 'retention_policy_assignment';
    const TYPE_FILE_VERSION_RETENTION      = 'file_version_retention';
    const TYPE_COLLABORATION               = 'collaboration';
    const TYPE_FILE_VERSION                = 'file_version';
    const TYPE_GROUP                       = 'group';
    const TYPE_GROUP_MEMBERSHIP            = 'group_membership';
    const TYPE_USER                        = 'user';
    const TYPE_ENTERPRISE                  = 'enterprise';
    const TYPE_LOCK                        = 'lock';
    const TYPE_JWT                         = 'JWT';

    const CONTENT_TYPE_NAME         = 'name';
    const CONTENT_TYPE_DESCRIPTION  = 'description';
    const CONTENT_TYPE_FILE_CONTENT = 'file_content';
    const CONTENT_TYPE_COMMENTS     = 'comments';
    const CONTENT_TYPE_TAG          = 'tag';

    const GRANT_TYPE      = 'grant_type';
    const CODE            = 'code';
    const CLIENT_ID       = 'client_id';
    const CLIENT_SECRET   = 'client_secret';
    const TOKEN           = 'token';
    const BOX_DEVICE_ID   = 'box_device_id';
    const BOX_DEVICE_NAME = 'box_device_name';

    const ASSERTION   = 'assertion';
    const ALGORITHM   = 'RS256';
    const CONFIG_PATH = 'box.config.php';

    const HEADER_KEY_AUTH            = 'Authorization';
    const HEADER_VAL_V2_AUTH_STRING  = 'Bearer %s';
    const HEADER_KEY_USER_AGENT      = 'User-Agent';
    const HEADER_KEY_ACCEPT_ENCODING = 'Accept-Encoding';
    const HEADER_KEY_AS_USER         = 'As-User';
    const HEADER_CONTENT_MD5         = 'Content-MD5';

    const REFRESH_TOKEN          = 'refresh_token';
    const AUTHORIZATION_CODE     = 'authorization_code';
    const JWT_AUTHORIZATION_CODE = 'urn:ietf:params:oauth:grant-type:jwt-bearer';

    const IS_PLATFORM_ACCESS_ONLY = 'is_platform_access_only';

    const REQUEST_OPTION_MULTIPART_FILE = 'file';

    const QUERY_PARAM_ANCESTOR_FOLDER_IDS   = 'ancestor_folder_ids';
    const QUERY_PARAM_CONTENT_TYPES         = 'content_types';
    const QUERY_PARAM_CREATED_AT_RANGE      = 'created_at_range';
    const QUERY_PARAM_FIELDS                = 'fields';
    const QUERY_PARAM_FILE_EXTENSIONS       = 'file_extensions';
    const QUERY_PARAM_FILTER_TERM           = 'filter_term';
    const QUERY_PARAM_LIMIT                 = 'limit';
    const QUERY_PARAM_MAX_HEIGHT            = 'max_height';
    const QUERY_PARAM_MAX_WIDTH             = 'max_width';
    const QUERY_PARAM_MDFILTERS             = 'mdfilters';
    const QUERY_PARAM_MDFILTERS_FILTERS     = 'mdfilters.filters';
    const QUERY_PARAM_MDFILTERS_SCOPE       = 'mdfilters.scope';
    const QUERY_PARAM_MDFILTERS_TEMPLATEKEY = 'mdfilters.templateKey';
    const QUERY_PARAM_MIN_HEIGTH            = 'min_height';
    const QUERY_PARAM_MIN_WIDTH             = 'min_width';
    const QUERY_PARAM_OFFSET                = 'offset';
    const QUERY_PARAM_OWNER_USER_IDS        = 'owner_user_ids';
    const QUERY_PARAM_QUERY                 = 'query';
    const QUERY_PARAM_RECURSIVE             = 'recursive';
    const QUERY_PARAM_SCOPE                 = 'scope';
    const QUERY_PARAM_SIZE_RANGE            = 'size_range';
    const QUERY_PARAM_TRASH_CONTENT         = 'trash_content';
    const QUERY_PARAM_TYPE                  = 'type';
    const QUERY_PARAM_UPDATED_AT_RANGE      = 'updated_at_range';
    const QUERY_PARAM_USER_TYPE             = 'user_type';
    const QUERY_PARAM_VERSION               = 'version';

    const QUERY_PARAM_FIELDS_VALUE_EXPIRING_EMBED_LINK = 'expiring_embed_link';

    const GET          = 'GET';
    const POST         = 'POST';
    const PUT          = 'PUT';
    const DELETE       = 'DELETE';
    const OPTIONS      = 'OPTIONS';
    const HTTP_METHODS = [self::GET, self::POST, self::PUT, self::DELETE, self::OPTIONS];

    const BOX_ROOT_FOLDER_ID   = '0';
    const BOX_ROOT_FOLDER_NAME = 'All Files';

    const BOX_THUMBNAIL_FORMATS = [
        'png' => self::THUMBNAIL_PNG_PATH_STRING,
        'jpg' => self::THUMBNAIL_JPG_PATH_STRING,
    ];

    /** Error codes ************************************************************
     * https://developer.box.com/guides/api-calls/permissions-and-errors/common-errors/#common-error-codes
     **************************************************************************/

    // 400 Bad Request

    const ERROR_400_BAD_DIGEST                                  = 'bad_digest';
    const ERROR_400_BAD_REQUEST                                 = 'bad_request';
    const ERROR_400_CANNOT_MAKE_COLLABORATED_SUBFOLDER_PRIVATE  = 'cannot_make_collaborated_subfolder_private';
    const ERROR_400_COLLABORATIONS_NOT_AVAILABLE_ON_ROOT_FOLDER = 'collaborations_not_available_on_root_folder';
    const ERROR_400_CYCLICAL_FOLDER_STRUCTURE                   = 'cyclical_folder_structure';
    const ERROR_400_FOLDER_NOT_EMPTY                            = 'folder_not_empty';
    const ERROR_400_INVALID_COLLABORATION_ITEM                  = 'invalid_collaboration_item';
    const ERROR_400_INVALID_GRANT                               = 'invalid_grant';
    const ERROR_400_INVALID_LIMIT                               = 'invalid_limit';
    const ERROR_400_INVALID_OFFSET                              = 'invalid_offset';
    const ERROR_400_INVALID_REQUEST_PARAMETERS                  = 'invalid_request_parameters';
    const ERROR_400_INVALID_STATUS                              = 'invalid_status';
    const ERROR_400_INVALID_UPLOAD_SESSION_ID                   = 'invalid_upload_session_id';
    const ERROR_400_ITEM_NAME_INVALID                           = 'item_name_invalid';
    const ERROR_400_ITEM_NAME_TOO_LONG                          = 'item_name_too_long';
    const ERROR_400_PASSWORD_RESET_REQUIRED                     = 'password_reset_required';
    const ERROR_400_REQUESTED_PAGE_OUT_OF_RANGE                 = 'requested_page_out_of_range';
    const ERROR_400_REQUESTED_PREVIEW_UNAVAILABLE               = 'requested_preview_unavailable';
    const ERROR_400_SYNC_ITEM_MOVE_FAILURE                      = 'sync_item_move_failure';
    const ERROR_400_TASK_ASSIGNEE_NOT_ALLOWED                   = 'task_assignee_not_allowed';
    const ERROR_400_TERMS_OF_SERVICE_REQUIRED                   = 'terms_of_service_required';
    const ERROR_400_USER_ALREADY_COLLABORATOR                   = 'user_already_collaborator';
    const ERROR_400_METADATA_AFTER_FILE_CONTENTS                = 'metadata_after_file_contents';

    // 401 Unauthorized

    const ERROR_401_UNAUTHORIZED = 'unauthorized';

    // 403 Forbidden

    const ERROR_403_ACCESS_DENIED_INSUFFICIENT_PERMISSIONS = 'access_denied_insufficient_permissions';
    const ERROR_403_ACCESS_DENIED_ITEM_LOCKED              = 'access_denied_item_locked';
    const ERROR_403_ACCESS_FROM_LOCATION_BLOCKED           = 'access_from_location_blocked';
    const ERROR_403_FILE_SIZE_LIMIT_EXCEEDED               = 'file_size_limit_exceeded';
    const ERROR_403_FORBIDDEN                              = 'forbidden';
    const ERROR_403_INCORRECT_SHARED_ITEM_PASSWORD         = 'incorrect_shared_item_password';
    const ERROR_403_STORAGE_LIMIT_EXCEEDED                 = 'storage_limit_exceeded';
    const ERROR_403_USER_EMAIL_CONFIRMATION_REQUIRED       = 'user_email_confirmation_required';

    // 404 Not Found

    const ERROR_404_NOT_FOUND                   = 'not_found';
    const ERROR_404_NOT_TRASHED                 = 'not_trashed';
    const ERROR_404_PREVIEW_CANNOT_BE_GENERATED = 'preview_cannot_be_generated';
    const ERROR_404_TRASHED                     = 'trashed';

    // 405 Method Not Allowed

    const ERROR_405_METHOD_NOT_ALLOWED = 'method_not_allowed';

    // 409 Conflict

    const ERROR_409_CONFLICT                    = 'conflict';
    const ERROR_409_ITEM_NAME_IN_USE            = 'item_name_in_use';
    const ERROR_409_NAME_TEMPORARILY_RESERVED   = 'name_temporarily_reserved';
    const ERROR_409_OPERATION_BLOCKED_TEMPORARY = 'operation_blocked_temporary';
    const ERROR_409_RECENT_SIMILAR_COMMENT      = 'recent_similar_comment';
    const ERROR_409_USER_LOGIN_ALREADY_USED     = 'user_login_already_used';

    // 410 Gone

    const ERROR_410_SESSION_EXPIRED = 'session_expired';
    const ERROR_410_UPLOAD_FAILED   = 'upload_failed';

    // 411 Length Required

    const ERROR_411_LENGTH_REQUIRED = 'length_required';

    // 412 Precondition Failed

    const ERROR_412_PRECONDITION_FAILED            = 'precondition_failed';
    const ERROR_412_SYNC_STATE_PRECONDITION_FAILED = 'sync_state_precondition_failed';

    // 413 Request Entity Too Large

    const ERROR_412_REQUEST_ENTITY_TOO_LARGE = 'request_entity_too_large';

    // 415 Unsupported Media Type

    const ERROR_415_UNSUPPORTED_MEDIA_TYPE = 'unsupported_media_type';

    // 429 Too Many Requests

    const ERROR_429_RATE_LIMIT_EXCEEDED = 'rate_limit_exceeded';

    // 500 Internal Service Error

    const ERROR_500_INTERNAL_SERVER_ERROR = 'internal_server_error';

    // 502 Bad Gateway

    const ERROR_502_bad_gateway = 'bad_gateway';

    // 503 Unavailable

    const ERROR_503_UNAVAILABLE = 'unavailable';
}