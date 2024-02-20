<?php

namespace App\Http\Controllers;

use BendeckDavid\GraphqlClient\Facades\GraphQL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Analysis flow:
 * Get the report ready:
 * 1. Fetch latest reports for a zone
 * 1.a It's impossible to retrieve a list based on single encounterIDs (you can't get all reports with Fyrakk kills)
 *     - You must go into every report and check if it contains the encounterID, and if it's a kill.
 *     - Alternatively, we can use the rankings page, but this biases heavily towards cheese mechanics and speedruns.
 * 2. Fetch fights from a single report
 * 2.a Exclude wipes
 * 3. Fetch events from a single fight
 * 3.a startTime should always be 0 on the first request. This part of the data holds information about players, encounterStart time (needed for analysing actions).
 * 3.b nextPageTimestamp is used to paginate through the data. If it's null, then we're done.
 * 3.c Only reportData->report->events->data should be merged. The rest of the data is not needed.
 *
 * Group actions based on either serverframes (33ms) or some other time unit (100ms would be better).
 *
 * To get casts towards the raid, check if the sourceID is .
 * */

class WarcraftLogController extends Controller
{
    public function test()
    {
        $allFights = self::send(self::fightsFromSingleReportQuery('bYFJRd34ChVyBNxw'))->reportData->report->fights;

        // exclude wipes
        $allFights = array_filter($allFights, function ($fight) {
            return $fight->kill;
        });

        $fightID = $allFights[36]->id; // Fyrakk kill

        $response = self::send(self::fightReportQuery('bYFJRd34ChVyBNxw', $fightID));

        $startTime = 0;
        dd($response->reportData->report);

        return self::send(self::fightReportQuery('bYFJRd34ChVyBNxw', $fightID));
    }

    private static function reportsForZone(int $zoneID): string
    {
        $query = 'reportData {reports(zoneID: :id){data {code startTime endTime guild{ name}}}}';
        return self::replace($query, [':id' => $zoneID]);
    }

    private static function fightsFromSingleReportQuery(string $reportID): string
    {
        $query = 'reportData{report(code: :code){fights{id
				kill
				encounterID
				difficulty
			  name}}}';
        return self::replace($query, [':code' => $reportID]);
    }

    private static function fightReportQuery(string $reportID, int $fightID, int $startTime = 0): string
    {
        $endTime = self::currentMS();
        $query = '	reportData{
		report (code: :code) {
		  startTime
		  endTime
		  events(fightIDs: :id startTime: :start endTime: :end){
		        nextPageTimestamp
				data
			}
		}
	}';

        return self::replace($query, [':code' => $reportID, ':id' => $fightID, ':start' => $startTime, ':end' => $endTime]);
    }

    private static function playerDataFromSingleReportQuery(string $reportID): string
    {
        $query = 'reportData{report(code: :code){masterData{actors{id name type}}}}';
        return self::replace($query, [':code' => $reportID]);
    }

    private static function replace($query, $bindings)
    {
        foreach ($bindings as $key => $value) {
            if (is_string($value))
                $value = '"' . $value . '"';
            $query = str_replace($key, $value, $query);
        }
        return $query;
    }

    public static function send(string $query)
    {
        return GraphQL::query($query)
            ->get('json');
    }

    private static function currentMS()
    {
        return round(microtime(true)) * 1000;
    }
}
