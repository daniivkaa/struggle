<?php

namespace App\Repository;

interface GameRepositoryInterface
{
    public function getGamesForAjax(int $competitionId): array;
}