#!/usr/bin/php -q
<?php
date_default_timezone_set('UCT');

$dryrun     = FALSE;
$interval   = '24 hours';
$keep_for   = '10 Days';
$volumes    = array('vol-********');
$api_key    = 'AKIAIXXXXXXXXXXXXXXX';
$api_secret = 'IzMni.........................emQKct';
$ec2_region = 'us-east-1';

require 'aws/aws-autoloader.php';

use Aws\Ec2\Ec2Client;

$client = Ec2Client::factory(
    array(
         'key'    => $api_key,
         'secret' => $api_secret,
         'region' => $ec2_region
    )
);

$db = json_decode(file_get_contents(__DIR__.'/db.json'), TRUE);

$snapshots = array();
foreach($db AS $key => $snapshot)
{
    if(!empty($snapshots[$snapshot['volume']]))
    {
        if($snapshot['time'] > $snapshots[$snapshot['volume']]['time'])
        {
            $snapshots[$snapshot['volume']] = $snapshot;
        }
    }
    else
    {
        $snapshots[$snapshot['volume']] = $snapshot;
    }

    if($snapshot['time'] < strtotime('- ' . $keep_for))
    {
        $client->deleteSnapshot(
            array(
                 'DryRun'     => $dryrun,
                 'SnapshotId' => $snapshot['id'],
            )
        );

        unset($db[$key]);
    }
}

foreach($volumes As $volume)
{
    if((!empty($snapshots[$volume])) && ($snapshots[$volume]['time'] > strtotime('-' . $interval)))
    {
        continue;
    }

    $result = $client->createSnapshot(
        array(
             'DryRun'      => $dryrun,
             'VolumeId'    => $volume,
             'Description' => 'Daily backup',
        )
    );

    $db[] = array(
        'volume' => $volume,
        'time'   => strtotime($result['StartTime']),
        'id'     => $result['SnapshotId']
  );
}

file_put_contents('/usr/local/ec2/db.json', json_encode($db));

return;