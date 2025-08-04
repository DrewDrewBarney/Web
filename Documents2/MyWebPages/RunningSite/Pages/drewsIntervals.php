<?php 

include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';


?>

<html>
    <?php makeHead("Drew's Intervals")->echo(); ?>

    <body>


        <?php
        makeBanner("Drew's Intervals App​", ['home' => 'index.php', "Drew's Apps" => 'myApps.php'])->echo();
        ?>


        <article>

            <h2>Introduction</h2>
                <?php Tag::make('a', 'Garmin Connect-IQ', ['href' => 'https://apps.garmin.com/en-US/apps/c742d8ad-6eec-4b73-b47a-c1a21839609f', 'class' => 'buttonRight'])->echo(); ?>   

            <image style = 'float:right; margin: 10px 0px 20px 50px;' src = "../Images/Icon.png"  class="centerImage20" >

            <p>The Drew's Intervals app seems simple enough but this is deceptive. Though easy to use it is complex and highly flexible.  
                It allows you to get structured workouts on the app from three sources:
            </p>

            <ul>
                <li>Creation in the app settings page of Garmin Connect</li>
                <li>Push to watch from Training Peaks using its workout JSON export facility</li>
                <li>Pull workouts from Today's Plan</li>

            </ul>



            <h2>Creating your own workouts in the app settings page of Garmin Connect</h2>
            <p>Drew's intervals allows you to write workout descriptions within the settings page for the application in a highly succinct format.</p>
            <h3>Example 1 - with pre-defined zones:</h3>

            <p><b>min run pw [5, 1-2] + 6 * ( [5, 4] + [2, 1-2] ) + [5, 1-2]</b></p>

            <p>in this example, a power based workout for running with the duration of each step in minutes is specified.  We see a 5 minute warmup in power zones
                1-2, then 6 repetitions of ( a 5 minutes active step in zones 4 with a recovery step of 2 minutes in zones 1-2) followed by a 5 minute cooldown in zones 1-2.</p>
            <p>Clearly, in this particular example, the app has to know your zones for each type of intensity measure and each type of workout.  These are also set up in 
                settings. Note that you can state a single zone or range of zones.  In fact, you can also specify a range of decimal fractions of a zone.</p>

            <h3>Example 2 - using the "raw" keyword:</h3>

            <p>In this example a power based workout for cycling is specified with the duration of each step in miles.  The number of Watts for each step has to 
                be stated explicitly rather than as a zone, range of zones or range of fractional zones</p>

            <p><b>raw mi cy pw [3, 150-200] + 6 * ( [1, 200-300] + [0.5, 150-200] ) + [3, 150-200]</b></p>

            <h3>Example 3 - using duration post-fix and intensity pre-fix specifiers:</h3>

            <p>In this example we specify the duration and intensity of a run for each step, allowing a mixed workout</p>

            <p><b>raw ru [1 mi, hr 120-140] + 6 * ( [1 min, pw 280-320] + [0.5 min, hr 120-160] ) + [1 mi, hr 120-150]</b></p>

            <h3>Zones</h3>
            <p>Zones are set up in the settings page of the Garmin app.  Zones have to be based on a lactate threshold value, that is a lactate threshold pace, 
                power or heart rate for each activity type.  Zones are then described as percentages of the lactate threshold [pace|power|HR] for each activity type.
                You only need to set up zones for the activities and intensity measures you are interested in.  You can select the number of zones you require.  You also have the option of using a named zone
                regime based on the entered lactate threshold rather than entering in the zones explicitly.  (An example would be the zones from Matt Fitzgerald's 
                80/20 running).

        </article>

        <?php makeFooter()->echo(); ?>


    </body>
</html>
