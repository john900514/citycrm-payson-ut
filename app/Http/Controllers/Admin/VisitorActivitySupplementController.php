<?php

namespace AnchorCMS\Http\Controllers\Admin;

use App\VisitorActivity;
use App\VisitorActivityArchive;
use App\Http\Controllers\Controller;

class VisitorActivitySupplementController extends Controller
{
    protected $activity, $archive;

    public function __construct(VisitorActivity $act, VisitorActivityArchive $arc)
    {
        $this->activity = $act;
        $this->archive = $arc;
    }

    public function visitor_activity($record_id)
    {
        $args   = [
            'crud'  => $this->activity,
            'entry' => [] //requires a default value in case the db call fails or something
        ];

        $record = $this->activity->find($record_id);

        if($record)
        {
            $args['entry'] = $record;
        }

        return view('city-crm.cms.pages.view-more-activity', $args);
    }

    public function archive_visitor_activity($record_id)
    {
        $args   = [
            'crud'  => $this->archive,
            'entry' => [] //requires a default value in case the db call fails or something
        ];

        $record = $this->archive->find($record_id);

        if($record)
        {
            $args['entry'] = $record;
        }

        return view('city-crm.cms.pages.view-more-activity', $args);
    }

    public function  archive_lead_visitor_activity($lead_id, $activity_id)
    {
        $results = ['success' => false, 'reason' => 'Unknown Activity ID'];

        // Find the Activity Record or Fail!
        $leader_record = $this->archive->find($activity_id);

        if(is_null($leader_record))
        {
            $leader_record = $this->activity->find($activity_id);
        }

        if(!is_null($leader_record))
        {
            // Use the record's IP and locate the rest of them!
            $ip = $leader_record->ip;

            if(!is_null($leader_record->url_parameters))
            {
                //$url_params = json_decode($leader_record->url_parameters, true);
                $url_params = $leader_record->url_parameters;
                $gunk = json_encode($leader_record->url_parameters);

                if(!empty($url_params) && array_key_exists('utm_source', $url_params))
                {
                    $new_date = new \DateTime($leader_record->created_at);
                    $new_date->modify('-2 hour');
                    $new_date->format('Y-m-d H:i:s');
                    $new_date = date('Y-m-d H:i:s', $new_date->getTimestamp());

                    $archive_records = $this->archive->whereIp($ip)
                        ->whereBetween('created_at', [$new_date, $leader_record->created_at])
                        ->get();

                    $activity_records = $this->activity->whereIp($ip)
                        ->whereBetween('created_at', [$new_date, $leader_record->created_at])
                        ->get();
                }
                else
                {
                    $archive_records = $this->archive->whereIp($ip)->get();
                    $activity_records = $this->activity->whereIp($ip)->get();
                }

            }
            else
            {
                $archive_records = $this->archive->whereIp($ip)->get();
                $activity_records = $this->activity->whereIp($ip)->get();
            }

            /**
             * STEPS
             *  @todo - peruse the stack or records, and if the lead, referral or conversion ids show up not links to the lead in question,
             *  @todo - snag those records!
             * 3. Save the Ip for the mappy
             * 4.
             */

            // Send back all the records
            $results = ['success' => true, 'ip' => $ip, 'records' => [
                'archive' => $archive_records->toArray(),
                'active' => $activity_records->toArray()
            ]];
        }


        return response($results, 200);
    }

    public function  lead_visitor_activity($lead_id, $activity_id)
    {
        $results = ['success' => false, 'reason' => 'Unknown Activity ID'];

        return response($results, 200);
    }
}
