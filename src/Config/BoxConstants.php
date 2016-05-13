<?php
namespace Box\Config;
abstract class BoxConstants {
    const BOX_API_HOST_URI_STRING = "https://app.box.com/api/";
    const BOX_API_URI_STRING = "https://api.box.com/2.0/";
    const BOX_API_JWT_STRING = "https://api.box.com/";
    const BOX_UPLOAD_API_URI_STRING = "https://upload.box.com/api/2.0/"; 
    
    const AUTH_CODE_STRING = "oauth2/authorize";
    const AUTH_TOKEN_ENDPOINT_STRING = "oauth2/token";
    const REVOKE_ENDPOINT_STRING = "oauth2/revoke";
    
    const FOLDERS_STRING = "folders/";
    const GROUPS_STRING = "groups/";
    const GROUP_MEMBERSHIP_STRING = "group_memberships/";
    const FILES_STRING = "files/";
    const FILES_UPLOAD_STRING = "files/content";
    const FILES_NEW_VERSION_STRING = "files/%s/content";
    const COMMENTS_STRING = "comments/";
    const SEARCH_STRING = "search";
    const USER_STRING = "users/";
    const COLLABORATIONS_STRING = "collaborations/";
    const RETENTION_POLICIES_STRING = "retention_policies/";
    const RETENTION_POLICY_ASSIGNMENTS_STRING = "retention_policy_assignments/";
    const FILE_VERSION_RETENTIONS_STRING = "file_version_retentions";
    
    const AUTH_CODE_JWT_ENDPOINT_STRING = self::BOX_API_JWT_STRING . self::AUTH_TOKEN_ENDPOINT_STRING;
    const AUTH_CODE_ENDPOINT_STRING = self::BOX_API_HOST_URI_STRING . self::AUTH_CODE_STRING;
    const FOLDERS_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::FOLDERS_STRING;
    const GROUPS_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::GROUPS_STRING;
    const GROUP_MEMBERSHIP_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::GROUP_MEMBERSHIP_STRING;
    const FILES_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::FILES_STRING;
    const FILES_UPLOAD_ENDPOINT_STRING = self::BOX_UPLOAD_API_URI_STRING . self::FILES_UPLOAD_STRING;
    const FILES_NEW_VERSION_ENDPOINT_STRING = self::BOX_UPLOAD_API_URI_STRING . self::FILES_NEW_VERSION_STRING;
    const COMMENTS_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::COMMENTS_STRING;
    const SEARCH_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::SEARCH_STRING;
    const USER_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::USER_STRING;
    const COLLABORATIONS_ENDPOINT_STRING = self::BOX_API_URI_STRING . self::COLLABORATIONS_STRING;
    
    const ITEMS_PATH_STRING = "%s/items";
    const VERSIONS_PATH_STRING = "%s/versions";
    const COPY_PATH_STRING = "%s/copy";
    const COMMENTS_PATH_STRING = "%s/comments";
    const THUMBNAIL_PATH_STRING = "%s/thumbnail.png";
    const PREVIEW_PATH_STRING = "%s/preview.png";
    const TRASH_PATH_STRING = "%s/trash";
    const DISCUSSIONS_PATH_STRING = "%s/discussions";
    const COLLABORATIONS_PATH_STRING = "%s/collaborations";
    const TRASH_ITEMS_PATH_STRING = "%s/items";
    const TRASH_FOLDER_PATH_STRING = "%s/trash";
    const GROUP_MEMBERSHIP_PATH_STRING = "%s/memberships";
    const CONTENT_PATH_STRING = "%s/content";
    const RETENTION_POLICY_ASSIGNMENTS_ENDPOINT_STRING = "%s/assignments"; 
    
    const TYPE_FILE = "file";
    const TYPE_FOLDER = "folder";
    const TYPE_COMMENT = "comment";
    const TYPE_WEB_LINK = "web_link";
    const TYPE_RETENTION_POLICY = "retention_policy";
    const TYPE_RETENTION_POLICY_ASSIGNMENT = "retention_policy_assignment";
    const TYPE_FILE_VERSION_RETENTION = "file_version_retention";
    const TYPE_COLLABORATION = "collaboration";
    const TYPE_FILE_VERSION = "file_version";
    const TYPE_GROUP = "group";
    const TYPE_GROUP_MEMBERSHIP = "group_membership";
    const TYPE_USER = "user";
    const TYPE_ENTERPRISE = "enterprise";
    const TYPE_LOCK = "lock";
    const TYPE_JWT = "JWT";
    
    const GRANT_TYPE = "grant_type";
    const CODE = "code";
    const CLIENT_ID = "client_id";
    const CLIENT_SECRET = "client_secret";
    const TOKEN = "token";
    const BOX_DEVICE_ID = "box_device_id";
    const BOX_DEVICE_NAME = "box_device_name";
    
    const ASSERTION = "assertion";
    const ALGORITHM = "RS256";
    const CONFIG_PATH = "box.config.php";
    
    const AUTH_HEADER_KEY = "Authorization";
    const V2_AUTH_STRING = "Bearer %s";
    const USER_AGENT = "User-Agent";
    const ACCEPT_ENCODING = "Accept-Encoding";
    const AS_USER = "As-User";

    const REFRESH_TOKEN = "refresh_token";
    const AUTHORIZATION_CODE = "authorization_code";
    const JWT_AUTHORIZATION_CODE = "urn:ietf:params:oauth:grant-type:jwt-bearer";
    const FIELDS = "fields";
    
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";
    const OPTIONS = "OPTIONS";
    const HTTP_METHODS = [self::GET, self::POST, self::PUT, self::DELETE, self::OPTIONS];
}