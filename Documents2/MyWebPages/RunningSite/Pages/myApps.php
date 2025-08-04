<?php
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

list($html, $head, $body) = makePage("Drew's Resources for Athletes", ['home' => 'index.php']);

$body->makeChild('article')
        ->addChild(
                makeImageTileMenu(
                        [
                            ['../Images/DI1.png', 
                                "Download workouts from Training Peaks, Today'sPlan or build them yourself with a fully featured workout description language in Garmin Connect."
                                . "Integrates with Stryd power.",
                                'drewsIntervals.php'
                                ],
                            ['../Images/DI2.png', 
                                'Integrated with Final Surge. Integrates with Stryd power.',
                                'https://apps.garmin.com/en-US/apps/5cabdc88-4da5-41c4-95d9-dba7236beb5b'
                                ]
                        ]
                )
);


$body->addChild(makeFooter());

$html->echo();
