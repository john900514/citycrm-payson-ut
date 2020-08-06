<?php

namespace AnchorCMS\Actions\Reporting;

use AnchorCMS\VisitorActivity;

class GetSpecificVisitorActivity
{
    protected $activity, $archive;

    public function __construct(VisitorActivity $activity)
    {
        $this->activity = $activity;
    }

    public function execute(string $ip = '')
    {
        $results = ['success' => false, 'reason' => 'No Input Supplied'];

        if($ip != '')
        {
            $realtime_history = [];

            // Get total times IP address has been logged on the site
            $total_visit_count =  $this->activity->getTotalVisitCountByIp($ip);
            $daily_visit_count = 0;

            if($total_visit_count !== false)
            {
                if($total_visit_count > 0)
                {
                    // Get the total times per day that ip was logged on the site
                    $daily_visit_count = $this->activity->getTotalDailyVisitCountByIp($ip);

                    if($daily_visit_count)
                    {
                        // get the visitor_activity log to make a curated history
                        $realtime_history = $this->activity->getVisitorLogByIp($ip);
                        if(count($realtime_history) > 0)
                        {
                            $realtime_history = $realtime_history->toArray();
                            foreach($realtime_history as $idx => $hist)
                            {
                                $realtime_history[$idx]['type'] = 'realtime';
                            }
                        }
                    }
                    /*
                    else
                    {
                        $results['reason'] = 'Could Not Derive Daily Activity';
                    }
                    */

                }
                /*
                else
                {
                    $results['reason'] = 'No Activity Available';
                }
                */
            }

            // Curate Response
            $history = $realtime_history;
            $results = [
                'success' => true,
                'activity' => [
                    'total' => intVal($total_visit_count),
                    'daily' => intVal($daily_visit_count),
                    'history' => $history
                ]

            ];

            /*
            else
            {
                $results['reason'] = 'Invalid Input Supplied';
            }
            */
        }

        return $results;
    }

}
