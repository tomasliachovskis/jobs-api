<?php

namespace App\Services;

use App\Jobs\ScrapeJob;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use Exception;

class JobService
{
    public const STATUS_QUEUED = 'Queued';
    public const STATUS_EXECUTING = 'Executing';
    public const STATUS_COMPLETED = 'Completed';

    /**
     * Perform the web scraping
     */
    public function scrape(string $jobId)
    {
        $this->updateJobStatus($jobId, self::STATUS_EXECUTING);

        $job = $this->getJobData($jobId);

        $urls = $job['urls'];
        $selectors = $job['selectors'];

        $results = [];

        foreach ($urls as $url) {
            $result = [
                'url' => $url,
                'success' => true,
                'scraped_data' => [],
            ];

            try {
                $response = Http::get($url);
            } catch (Exception $e) {
                $result['success'] = false;
                continue;
            }

            $crawler = new Crawler($response);

            foreach ($selectors as $selector) {
                $scrapedData = [
                    'selector' => $selector,
                    'values' => $crawler->filter($selector)->each(fn($node) => $node->text()),
                ];
                $result['scraped_data'][] = $scrapedData;
            }

            $results[] = $result;
        }

        $job['scraped_data'] = $results;
        $job['scraped_at'] = now();
        $job['status'] = self::STATUS_COMPLETED;

        $this->setRedisData($jobId, $job);
    }

    /**
     * Update the job status in Redis.
     */
    protected function updateJobStatus(string $status, string $jobId)
    {
        $data = $this->getJobData($jobId);
        $data['status'] = $status;
        $this->setRedisData($jobId, $data);
    }

    /**
     * Store job data in Redis.
     */
    protected function setRedisData($id, $data)
    {
        Redis::set("job:{$id}", json_encode($data));
    }

    /**
     * Retrieve job data from Redis.
     */
    public function getJobData($id):array
    {
        $data = Redis::get("job:{$id}");

        return json_decode($data, true) ?? [];
    }

    /**
     * Delete job from Redis.
     */
    public function deleteJob($id)
    {
        Redis::del("job:{$id}");
    }

    public function createJob(array $parameters):string
    {
        $jobId = uniqid('job_');

        $jobData = [
            'id' => $jobId,
            'status' => JobService::STATUS_QUEUED,
            'urls' => $parameters['urls'],
            'selectors' => $parameters['selectors'],
            'scraped_data' => null,
            'created_at' => now(),
        ];

        Redis::set("job:{$jobId}", json_encode($jobData));

        ScrapeJob::dispatch($jobId);

        return $jobId;
    }
}
