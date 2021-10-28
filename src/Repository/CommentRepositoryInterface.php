<?php

namespace App\Repository;

interface CommentRepositoryInterface
{
    public function getCommentsForAjax(int $targetUserId, int $secondUserId, int $messageId): array;
}