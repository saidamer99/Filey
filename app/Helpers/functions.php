<?php
function checkUser()
{
    return request()->user('api');
}

function groupDescription($group): string
{
    $owner = $group->owner;
    $is_members = $group->members()->where('id', request()->user());
    if (!$owner) {
        return "public";
    }
    if ($owner->id == request()->user()->id) {
        return "owner";
    }
    if ($is_members) {
        return "member";
    }
    return "";
}

function getFileStatus($file): string
{
    switch ($file->status) {
        case "free":
            return "free";
        case "preserved":
            return "preserved by " . $file->editor->name;
        default:
            return "not defined";
    }
}
