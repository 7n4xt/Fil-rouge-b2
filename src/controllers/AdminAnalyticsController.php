<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/AnalyticsModel.php';

class AdminAnalyticsController
{
    private AnalyticsModel $analytics;

    public function __construct(PDO $pdo)
    {
        $this->analytics = new AnalyticsModel($pdo);
    }

    public function index(): void
    {
        $data = $this->analytics->snapshot();
        require __DIR__ . '/../views/adminAnalytics.php';
    }
}
