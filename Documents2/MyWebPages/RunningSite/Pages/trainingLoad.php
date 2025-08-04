<?php
//session_start();
include_once '../../Common/PHP/roots.php';
include_once '../../Common/PHP/all.php';
include_once '../../RunningSite/PHP/runningAll.php';
include_once 'menu.php';
?>

<html>
    <?php makeHead("")->echo(); ?>


    <body>

        <?php
        $div = makeTopBar();
        $div->addChildren([
            makeMenu(MENU_HOME, 'basis.php'),
            makePageTitle('Underlying Concepts'),
            makeMenu(MENU_BASIS, 'trainingLoad.php'),
            makePageTitle('Training Load and Performance')
        ]);

        $div->echo();
        ?>

        <article>
            <h2>Training Load and Performance</h2>

            <figure style="width:25%;float: right; margin: 10px 0px 20px 60px; ">
                <img src ="../Images/Andersen-Schiess.jpeg" style="width:100%;border-radius: 30px;">
                <figcaption>
                    Gabriela Andersen-Schiess, 1884 Olympics (Courtesy of The I.O.L., 2018)
            </figure>


            <p>
                I come from a medical background.
                Medical science has an evidence base from clinical studies that typically involve thousands of randomly selected patients.
                Sport science is rather different as the subjects are not representative of the whole population and sample sizes typically run into a mere handful of athletes.
                We have to be wary of model curves with good yet statistically and practically insignificant correlations to experimental data.
            </p>

            <p>
                We have discussed individual training sessions and the use of training zones referenced to some correlate of lactate threshold.
                We will now look at the effect of multiple sessions of training on our performance.
                The ultimate goal is to perform well at some competition or series of competitions.
                Modeling performance in this way may guide us in our training both to perform well and to avoid over-training and injury.
            </p>

            <p>
                Typical models consists of two parts:
            </p>

            <ol class='OrderedList'>
                <li>
                    The first part is the calculation of the training load arising from a single episode of training. 
                    It is represented by a single value. 
                    Examples include RPE, TRIMP, RSS and TSS.
                </li>
                <li>
                    The second part uses the training load values from successive workouts to predict performance. 
                    Typically this is expressed as some function of fitness and fatigue where fitness and fatigue
                    are calculated from all the training loads and when they happened.
                    The output of such models is in reality a guide with no concrete way of determining that they are "correct" in value or form.
                    There is evidence however that they are of value.


                </li>
            </ol>

            <p>
                One scheme might simply be to subtract fatigue from fitness:
            </p>


<?php mountFormulaOnCard(makeEquation(['performance = fitness(loading) - fatigue(loading)']))->echo(); ?>

            <p>
                Whatever the form of the performance function takes, it needs to have passed a series of all load and time value pairs.
                The very first step therefore is to calculate training load.
                This is not straightforward and many of the proposed equations seem to be rather ad-hoc.
            </p>

            <h3>Training Load Scores</h3>

            <p>
                How does a single workout affect us?
                What training load has accrued.
                Unfortunately this is not easy to say exactly. 
                In fact it is hard to say exactly what training load is.
            </p>

            <ol>
                <li>
                    We know that one athletes hard is another's easy.
                </li>
                <li>
                    We know that as we get fitter the same level of activity gets easier.
                </li>            
                <li>
                    We also know that running a bit faster or generating a bit more power feels <b>quite a bit</b> harder and is <b>quite a bit</b> more fatiguing. 
                </li>
                <li>
                    We know that the longer the episode of training the more fatigued we get.
                </li>
            </ol>

            <p>
                A score of training load should take all these factors into account.
            </p>

            <p>
                The changes brought about by training are complex.
                Several things are consumed, produced, lost, eliminated then replaced over varying periods of time 
                e.g. ATP, CP, glucose, glycogen, fat, heat, water, CO<sub>2</sub> and lactate.
                Microscopic damage occurs which might degrade our performance in the short term but that then stimulates adaptation and improvement in the long run.
                It is difficult to conceive of a single value that could somehow represent the complexity of these changes.
            </p>


            <h4>Rate of Perceived Exertion RPE</h4>

            <p>
                This is the oldest way of assessing training load.  
                Typically an increasing score (e.g. Borg Scale) is assigned an increasing intensity number.
                This intensity number is then raised to a power to reflect that greater work intensities are a lot more fatiguing.
                When multiplied by time this gives a relative measure or workload.
                Although a little rule of thumb, such simple ways of assessment are of value.
                The ready availability of the means to measure and record various physiological parameters have however lead to methods based on these 
                that have largely superseded subjective assessment.
                One advantage of this is it automates the process as we do not have to enter our perception of how hard a workout was afterwards.
            </p>

            <h4>Training Impulse - TRIMP</h4>
            <p>
                One of the earliest non-subjective assessment of training load was developed by Prof. Eric W Bannister in 1990.
                He called it Training Impulse or TRIMP.
                TRIMP is a calculated value based on the proportion of cardiac reserve (Karvonen) obtained during a workout.
                If we call the proportion of heart rate reserve:
            </p> 


<?php mountFormulaOnCard(makeEquation(['P<sub>hrr</sub>', '=', makeFrac('HR<sub>avg_ex</sub> - HR<sub>rest</sub>', 'HR<sub>max</sub> - HR<sub>rest</sub>')]))->echo(); ?>


            <p>then</p>


<?php mountFormulaOnCard(makeEquation(['TRIMP', '=', 't', '×', 'P<sub>hrr</sub>', '×', '0.64', '×', 'e<sup>(𝝀 × P<sub>hrr</sub>)</sup>']), '𝝀 = 1.92 for a man; 𝝀 = 1.67 for a woman')->echo(); ?>


            <p>

            </p>
            <a class="buttonRight" href = "PDFs/TRIMP.pdf">TRIMP</a>

            <p>
                where t is the time in minutes.
                The initial step to deriving this formula was to simply make TRIMP proportional to the product of time and P<sub>hrr</sub> but this did not reflect the
                perceived much higher load with high P<sub>hrr</sub> and so an exponential factor was added.
                Although the average HR<sub>exercise</sub> is shown, these days we would summate lots of little TRIMPS over the duration of the workout.
                The general form of this equation seems right with an increasing slope of workload vs. P<sub>hrr</sub>.
                The exponential factor was designed to correlate with measured blood lactate levels.
                A strange attribute of this exponential factor is that the constant factor in the exponent differs between men and women.
                This means a male athlete would accrue a greater TRIMP when working at some P<sub>hrr</sub> than a female athlete at the same intensity.
                As there is no confidence interval stated for these factors we should be wary of this conclusion.
                It is also important to note that being based on heart rate the formula has to compensate for the squishing up of dynamic range as we approach maximum heart rate 
                and so will not be applicable to other intensity measures such as flat pace or power.
                We should expect such formulae to have a different form.
            </p>
            <p>
                To give a feel for this, say we have a male and a female athlete both with a resting hr of 60 and a maximum hr of 200.
                If he exercises with an average hr of 165 for 60 minutes then: 
            </p>

<?php mountFormulaOnCard(makeEquation(['TRIMP = 60 × 0.75 × 0.64 × e<sup>(1.92 × 0.75)</sup> &nbsp = 122']))->echo(); ?>




            <p>
                If she exercises with an average hr of 110 for 60 minutes then:
            </p>

<?php mountFormulaOnCard(makeEquation(['60', '×', '0.5', '×', '0.64', '×', 'e<sup>(1.67 × 0.5)</sup>', '=', '44']))->echo(); ?>


            <h4>
                Running Stress Score - RSS (Stryd)
            </h4>
            <a class="buttonRight" href='https://blog.stryd.com/2017/01/28/running-stress-score/'>RSS</a>

            <p>
                Stryd states that their running stress score has the form RSS = 100 × training duration × (Power/CP) ^ K though the value of K is not stated.
                This seems incorrect but a reasonable (if imperfect) fit is given by: 
            </p>



<?php mountFormulaOnCard(makeEquation(['RSS/min = 2/3 × P<sub>N</sub> &nbsp + P<sub>N</sub> <sup>4.9</sup>']), 'P<sub>N</sub> = Power/CP')->echo(); ?>

            <img class='centerImage50' src='../Images/RSS.png'>

            <p>
                Again the general form (upward curve) seems intuitively correct.
                The exponent of 4.9 is much greater than that used for a cycling power TSS which for a steady effort is 2.
                This is supposed to reflect the increasing workload of running with increasing pace.
            </p>

            <h4>Training Stress Score - TSS</h4>

            <p>
                The official definitions of TSS in my opinion hide its simplicity.
                It can be shown to be equivalent to:
            </p>


<?php
mountFormulaOnCard(
        makeEquation(['TSS', '=', makeFrac('seconds', makeEquation(['36', '×', 'FTP<sup>2</sup>'])), '×', 'RMS(<b>P<sup>2</sup></b>)']),
        'FTP = functional threshold power; RMS = root mean squares; <b>P<sup>2</sup></b> = the vector of squared power values; (with a 30s moving avg filter)')->echo();
?>



            <p style='clear: both;'></p>

            <a class="buttonRight" href='PDFs/TSS.pdf'>TSS Problems</a>

            <p>
                The idea behind using RMS is to account for a perception of additional workload where power is not constant but varies.
                Although widely used there are problems with this calculation of training stress.
                It does not produce a higher workload for a rapidly varying power output when compared to a slowly varying power output of the same amplitude of variation and mean power.
                The sum of the calculated TSS scores from two consecutive workouts does not equal the TSS of the two workouts taken together.
                Although it has been shown to be useful it cannot be optimal.
            </p>


            <h4>Excess Post-exercise Oxygen Consumption - EPOC</h4>

            <img style="float:right; width:40%; margin: 10px 0px 20px 50px;" src="../Images/EPOC.png">

            <p>
                As we would expect, the rate of oxygen consumption (VO<sub>2</sub>) is increased during a training activity compared to that at rest.
                But what happens after exercise? 
                It has been shown that it does not return immediately to the pre-exercise level as we might have expected.
                Instead it is increased and this increase slowly decays over time.
                The area above the pre and post VO<sub>2</sub> curves is the Excess Post-exercise Oxygen Consumption (EPOC).
                This value represents the oxygen required to restore the pre-exercise levels of the metabolites described above and also repair
                damage which is also an energy consuming process that requires oxygen. 
                This return to a desired optimal state is a general fundamental physiological principle (homeostasis).
                EPOC has been shown to increase fairly linearly with duration of exercise and exponentially with intensity of exercise as a percentage of VO<sub>2<sub>max</sub></sub>.

            </p>
            <a class="buttonRight" href='https://assets.firstbeat.com/firstbeat/uploads/2015/10/white_paper_epoc.pdf'>First Beat<br>EPOC</a>
            <p> 
                EPOC would seem a good candidate for a global measure of the load arising from an episode of training.
                The problem is it requires a training laboratory to measure it.
                It has been found however that EPOC can be predicted reasonably accurately from other measures.
                First Beat developed a method of predicting EPOC based on a prediction of %VO<sub>2<sub>max</sub></sub> in turn based upon heart rate.
                It seems a little tenuous but the predicted correlation is not too bad with an R<sup>2</sup> of 0.79.
                Not only that, but their is reasonable correlation between this and an older measure of intensity called TRIMP. 
                Parts of this white paper are published.
            </p>

            <h4>Heart Rate Variability - HRV</h4>

            <img style='float:right; width:33%; margin: 10px 0px 20px 50px;' src='../Images/RRInterval.png'>

            <p>
                Heart rate variability is the variation in the time interval between beats of the heart.
                It is an interesting if rather complex subject.
                It shows promise but as yet there is an insufficient evidence to prove its value in measuring training load.
                It is however being used as a recovery metric.
                The original and most direct way of obtaining HRV was with an electrocardiogram - ECG.
                This measures the electrical potential on the skin surface generated by the heart during its electrical cycle and plots it against time.
                It has a repeating pattern which includes a narrow upward spike in voltage termed the R wave which therefore defines a point in time.
                Heart rate variability is simply the variation in the time interval between successive R wave peaks.
                This variation is not however completely random but has a structure.
                This structure varies with time and activity.
                The study of heart rate variability is therefore complex requiring time, frequency or wavelet analysis.
            </p>
            <a class='buttonRight' href='https://imotions.com/blog/heart-rate-variability/'>HRV</a>

            <img style='float:right; width:33%; margin: 0px 0px 20px 50px;' src='../Images/Poincare.png'>

            <p>
                One interesting way of looking at the self-similarity of HRV is by using a Poincaré plot.             
            </p>



            <h3>Predicting Performance from Training Load</h3>

            <p>
                We have seen considerably varied methods of calculating the training load accumulated from a single workout.
                Although the general form of most seems in broad brush intuitively correct, more heavily weighting higher intensity activity, 
                they appear rather ad-hoc and it is difficult to find supporting evidence.
                Some studies have even suggested that RPE might be superior!
                If we take a leap of faith and accept the validity of a single measure of training load, the next step is to use the calculated training loads
                to predict performance at any point in time.
                This is again not straightforward.
            </p>

            <h4>The Fitness and Fatigue Model</h4>

            <p>
                Yet again this was first developed by E Banister.
                It makes the assumption that a training load contributes to both fitness and fatigue.
                It also assumes that any accrued fitness or fatigue decay in an exponential fashion, with different half-lives for fitness and fatigue.
                Typically the half life for fitness 𝝀 is long at around 42 days and only 7 days for fatigue 𝜸.
                If we consider the fitness present immediately following a first episode of training at time t<sub>0</sub> with load L<sub>0</sub>:
            </p>

<?php mountFormulaOnCard(makeEquation(['fit(t<sub>0</sub>) = L<sub>0</sub>']))->echo(); ?>

            <p>
                but this fitness decays over time with half life 𝝀 so at time t after this single workout the accrued fitness remaining is:
            </p>

<?php mountFormulaOnCard(makeEquation([' L<sub>0</sub> × e<sup><sup>-(t-t<sub>0</sub>)/</sup>𝝀</sup>']))->echo(); ?>


            <p>
                if we add in further training loads, the fitness is the sum of what is left remaining from each training load:                 
            </p>

<?php mountFormulaOnCard(makeEquation(['fit(t) = L<sub>0</sub> × e<sup><sup>-(t-t<sub>0</sub>)/</sup>𝝀</sup>  +  L<sub>1</sub> × e<sup><sup>-(t-t<sub>1</sub>)/</sup>𝝀</sup>
                    +  L<sub>2</sub> × e<sup><sup>-(t-t<sub>2</sub>)/</sup>𝝀</sup> &nbsp etc...']))->echo(); ?>




            <p>
                If the same is done for fatigue then performance is said to be:
            </p>


<?php mountFormulaOnCard(makeEquation(['perf(t) = 𝛼 × fit(t) - 𝛽 × fat(t)']))->echo(); ?>


            <p>
                The paper in the TRIMP link above shows good predictive behavior, but for only two athletes!
                I have found it difficult to find a really solid evidence base for performance prediction.
                Many of these algorithms are proprietary without a published evidence base in peer reviewed journals.
                I think they are a rough guide.
            </p>   

            <img style='width:100%;' src='../Images/FitnessFatiguePerformance.png'>

            <p>
                Here is one implementation of this model.  
                I have used a half-life for fitness of 42 days, and 7 days for fatigue.
                I have scaled fitness so that with a steady daily (lets say TRIMP) of 100 the curve approaches 100 asymptomatically.
                I have scaled fatigue so with the same training stimulus it approaches 50.
                I have arbitrarily decided upon a baseline performance of 50 as this is never zero.
                Performance is the space in between.
                In this example training starts at day 100 and continues to day 300 after which training stops.
                Performance swings negatively as fatigue initially dominates over fitness.
                Fitness slowly catches up so that performance becomes positive (we expect training to improve performance long term).    
                On stopping training fatigue decays more rapidly than fitness and we overshoot (the basis of tapering off before a race).
                Performance of course eventually declines to pre-training levels.
            </p>
            <p>
                Although put together in a rather ad-hoc way, this model, in broad brush, captures what most athletes experience during an extended training cycle.
            </p>

            <ul>
                <li>
                    The first run after a long pause is often a "honeymoon" run, better than expected.
                </li>

                <li>
                    In the first week or so, performance actually deteriorates.
                </li>

                <li>
                    Eventually we see improvement.
                </li>
                <li>
                    Our improvement, with the same training impulses, reaches a steady state and we stop improving.
                </li>
                <li>
                    When we stop (usually taper off) training our performance temporarily overshoots and we are ready to race.
                </li>

                <li>
                    If we stop training our fitness will again decline back to its base level.
                </li>
            </ul>

            <p>
                It is of course a gross simplification of training in which there are likely far more than two factors, fitness and fatigue.
                There are many similar models, many are proprietary and many do not have a published evidence base in peer reviewed journals.
                I think they can be a useful guide but should be combined with your own experience of what works for you.
            </p>

        </article>

<?php makeFooter()->echo(); ?>

    </body>
</html>
