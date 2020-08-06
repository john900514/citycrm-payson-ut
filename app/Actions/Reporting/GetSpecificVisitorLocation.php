<?php

namespace AnchorCMS\Actions\Reporting;

use AnchorCMS\IpAddresses;
use Ixudra\Curl\Facades\Curl;

class GetSpecificVisitorLocation
{
    protected $ip;

    public function __construct(IpAddresses $ip)
    {
        $this->ip = $ip;
    }

    public function execute($data)
    {
        $results = ['success' => false, 'reason' => 'Missing Input'];

        if(array_key_exists('ip', $data))
        {
            // Check IPs table for ip
            $record = $this->ip->lookUpIP($data['ip']);

            if(!$record)
            {
                if(is_array($data['ip']))
                {
                    $data['ip'] = $data['ip'][0];
                }
                // If not exists ping ipinfo.io for IP or fail
                // @todo - before firing out, check the limits or fail!
                $url = "https://ipinfo.io/{$data['ip']}/json";
                $response = Curl::to($url)
                    ->asJson(true)
                    ->get();

                if($response)
                {
                    if(array_key_exists('loc', $response))
                    {
                        $loc = explode(',',$response['loc']);

                        $payload = [
                            'ip' => $data['ip'],
                            'source' => 'ipinfo.io',
                            'ip_info' => $response,
                            'lat' => $loc[0],
                            'long' => $loc[1]
                        ];

                        //Cut a new record or fail
                        $record = $this->ip->insertNew($payload);

                        if(!$record)
                        {
                            // $todo - curate response to look like model;
                            $results['response'] = 'Could not log record';
                        }
                    }
                    else
                    {
                        $results = [
                            'success' => true,
                            'info' => [
                                'IP Address' => 'Local IP Address',
                                'City' => 'Local IP Address',
                                'State' => 'Local IP Address',
                                'Country' => 'Local IP Address',
                                'GPS' => 'Local IP Address'
                            ],
                            'coordinates' => [
                                'lat' => 27.950575,
                                'long' => -82.4571776,
                            ]
                        ];
                        return $results;
                    }

                }
                else
                {
                    $results['reason'] = 'Could not get IP address info';
                }
            }

            if($record)
            {
                // take model and curate response
                $record = $record->toArray();

                $results = [
                    'success' => true,
                    'info' => [
                        'IP Address' => $record['ip'],
                        'City' => $record['ip_info']['city'],
                        'State' => $record['ip_info']['region'],
                        'Country' => $record['ip_info']['country'],
                        'GPS' => $record['ip_info']['loc']
                    ],
                    'coordinates' => [
                        'lat' => $record['lat'],
                        'long' => $record['long'],
                    ]
                ];

                if(array_key_exists('Zip', $record['ip_info']))
                {
                    $results['info']['Zip'] = $record['ip_info']['postal'];
                }

                if(array_key_exists('org', $record['ip_info']))
                {
                    $results['info']['Organization'] = $record['ip_info']['org'];
                }

                if(array_key_exists('timezone', $record['ip_info']))
                {
                    $results['info']['Time Zone'] = $record['ip_info']['timezone'];
                }

                if(array_key_exists('hostname', $record['ip_info']))
                {
                    $results['info']['Host'] = $record['ip_info']['hostname'];
                }
            }
        }

        return $results;
    }
}
