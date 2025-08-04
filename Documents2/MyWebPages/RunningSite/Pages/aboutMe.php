<?php 
session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';

?>
<html>
    <?php 
        makeHead("Drew's Resources")->echo();
        
    ?>
    <body>


        <?php
        
        $topBar = makeTopBar();
        $topBar->addChild(makeMenu(MENU_HOME, 'aboutMe.php'));
        $topBar->addChild((makePageTitle('About the Author')));
       
        $topBar->echo();
        
        
       ?>

        <article>           
            <h2>About the Author</h2>

            <img style='float:right; width:20% ; margin: 30px 0px 20px 50px; border-radius: 15px;' src ='../Images/Drew.jpeg'>


            <p>
                I am a 

                <?php
                $dob = new DateTime('1958/04/02');
                $now = new DateTime('now');
                $age = $dob->diff($now);
                echo $age->y;
                ?>

                yr old retired general practitioner though have worked as a software engineer in the past.
                I am a also lifelong runner and running enthusiast.
            </p>
            <a class='buttonRight' href = "https://www.runbritainrankings.com/runners/profile.aspx?athleteid=246959">Run Britain</a>
            <p>
                I am not an elite athlete and I am definitely slowing down with age!
                I enjoy most outdoor pursuits including cycling and swimming and have competed in shorter triathlons.
            </p>
            <p>
                I am not a sports scientist but have attended and enjoyed several courses organised by the then British Association of Sports Medicine (BASM, now BASEM) 
                at The National Sports Center in Lilleshall, Shrophsire, United Kingdom.
                The information presented here are my own ideas and opinions.
            </p>
            <p>
                If you believe there are important factual errors feel free to contact me and discuss them and if appropriate I can incorporate changes if needed.
                All opinions should be respected and this document is essentially my own views at the time of writing.
            </p>
        </article>
        <?php makeFooter()->echo(); ?>

    </body>
</html>