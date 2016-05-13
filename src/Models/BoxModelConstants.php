<?php
namespace Box\Models;
abstract class BoxModelConstants {
    const BOX_TYPE_FILE = 'file';
    const BOX_TYPE_DISCUSSION = 'discussion';
    const BOX_TYPE_COMMENT = 'comment';
    const BOX_TYPE_FOLDER = 'folder';
    const BOX_TYPE_RETENTION_POLICY = 'retention_policy';
    const BOX_TYPE_ENTERPRISE = 'enterprise';
    const BOX_TYPE_USER = 'user';
    const BOX_TYPE_GROUP = 'group';
    const BOX_TYPE_WEB_LINK = 'web_link';
    
    const BOX_SHARED_LINK_ACCESS_TYPE_OPEN = "open";
    const BOX_SHARED_LINK_ACCESS_TYPE_COMPANY = "company";
    const BOX_SHARED_LINK_ACCESS_TYPE_COLLABORATORS = "collaborators";

    const BOX_SYNC_STATE_TYPE_SYNCED = "synced";
    const BOX_SYNC_STATE_TYPE_NOT_SYNCED = "not_synced";
    const BOX_SYNC_STATE_TYPE_PARTIALLY_SYNCED = "partially_synced";
        
    const BOX_SORT_BY_TYPE = "Type";
    const BOX_SORT_BY_NAME = "Name";
       
    const BOX_SORT_DIRECTION_ASC = "ASC";
    const BOX_SORT_DIRECTION_DESC = "DESC";
    
    const BOX_PERMISSION_TYPE_OPEN = "Open";
    const BOX_PERMISSION_TYPE_COMPANY = "Company";
    
    const BOX_FOLDER_AND_FILE_NAME_CHARACTER_LIMIT = 255;
}