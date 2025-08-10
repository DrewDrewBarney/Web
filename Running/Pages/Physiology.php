<?php

class Physiology extends Page {

    function __construct() {
        parent::__construct('about');
    }

    protected function buildPage(): void {
        parent::buildPage();

        $article = $this->body->makeChild('article', ' ');
        $article->makeChild('h2', 'Introduction');
        $figure = $article->makeChild('figure', ' ', ['style' => 'width:25%;float: right; margin: 10px 0px 20px 50px; ',]);
        $figure->makeChild('img', '', ['src' => '../Images/Zatopek.png', 'style' => 'width:100%;border-radius: 30px;',]);
        $figure->makeChild('figcaption', ' Emile Zatopek, Courtesy of Ghetty Images ');
        $article->makeChild('p', ' Phased Zonal training (PZT) of which Interval Training is but one example uses the idea of carrying out an activity in different zones of intensity during different phases of a workout. A phase involves the athlete working at a certain intensity for a specified duration or distance. A complete workout is a contiguous series of different prescribed phases. ');
        $article->makeChild('p', ' But what is the point of PZT? Well, if you go out and run the same same distance at the same level of effort each day then you will quite quickly reach a point where you stop improving. Of course if you are happy with this then fine. I was and ran like this for decades, enjoying the aesthetics of running and not being particularly interested in improvement or competition. On the other hand if you wish to compete, to reach your full potential you are going to have to use a different strategy and "polarise" your training. This involves doing a mixture of long low intensity sessions, shorter "tempo" training and highly intense interval training interspersed with periods of rest, cross training, core stability, strength and flexibility training. You will also need to progressively increase the intensity of your training while avoiding injury. Carefully controlling the duration and intensity of your effort with training zones both within and between workouts is essential. ');
        $p = $article->makeChild('p', ' ');
        $p->makeChild('a', '80 20 Running', ['class' => 'Button', 'href' => 'https://www.youtube.com/watch?v=VkDYXUI1x08',]);
        $article->makeChild('p', ' But what are zones? What is their basis? There are numerous schemes available. So many in fact as to overwhelm someone starting out with structured training. What scheme should you choose? Luckily there is evidence that it does not really matter that much. The most important thing is that your training has a structure and is varied. It should both progressively improve performance and avoid injury. For elite athletes however the margin between performance improvement and injury is very narrow indeed and objective measures of the intensity of training are needed. ');
        $article->makeChild('p', ' Some schemes are in my opinion of more value than others. They are discussed below. ');
        $article->makeChild('h2', 'Subjective Exercise Intensity "Measures"');
        $article->makeChild('h3', 'RPE Scales ');
        $article->makeChild('p', ' Historically the earliest exercise intensity zones were based on scales of "Rate" of Perceived Exertion (RPE). An example would be the scheme proposed and developed by Borg. (The slightly odd presentation was so the number associated with the description, times ten, approximates to a typical heart rate in a young athlete). Although of value in the absence of a more objective measures and with some evidence of a weak correlation with objective exertion intensities (when compared with heart rate) the scales are overly granular for a subjective measure. They give a sense of precision that is simply not there. Personally I have never felt able to accurately judge my level of effort during 40 yrs of road running. I think most athletes will have on occasion found running a familiar route much harder than usual and then been surprised that it took no more time than usual to complete it. Subjective estimates of intensity are influenced by so many factors including the complexity of the human psyche. I doubt any scheme, however sophisticated, could adequately account for that! ');
        $table = $article->makeChild('table', ' ', ['class' => 'BorgTable',]);
        $table->makeChild('caption', " Borg's Rating of Perceived Exertion (RPE) Scale ");
        $tr = $table->makeChild('tr', ' ');
        $tr->makeChild('th', 'Perceived Exertion Rating');
        $tr->makeChild('th', 'Description of Exertion');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightblue',]);
        $tr->makeChild('td', '6');
        $tr->makeChild('td', 'No exertion; sitting and resting');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightblue',]);
        $tr->makeChild('td', '7');
        $tr->makeChild('td', 'Extremely light');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightgreen',]);
        $tr->makeChild('td', '8');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightgreen',]);
        $tr->makeChild('td', '9');
        $tr->makeChild('td', 'Very light');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightgreen',]);
        $tr->makeChild('td', '10');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: lightgreen',]);
        $tr->makeChild('td', '11');
        $tr->makeChild('td', 'Light');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: yellow',]);
        $tr->makeChild('td', '12');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: yellow',]);
        $tr->makeChild('td', '13');
        $tr->makeChild('td', 'Somewhat hard');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: yellow',]);
        $tr->makeChild('td', '14');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: yellow',]);
        $tr->makeChild('td', '15');
        $tr->makeChild('td', 'Hard');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: yellow',]);
        $tr->makeChild('td', '16');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: orange',]);
        $tr->makeChild('td', '17');
        $tr->makeChild('td', 'Vary hard');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: orange',]);
        $tr->makeChild('td', '18');
        $tr->makeChild('td');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: orange',]);
        $tr->makeChild('td', '19');
        $tr->makeChild('td', 'Extremely hard');
        $tr = $table->makeChild('tr', ' ', ['style' => 'background-color: orange',]);
        $tr->makeChild('td', '20');
        $tr->makeChild('td', 'Maximal exertion');

        $article->makeChild('p', ' I have found the development of objective physiological measures far more useful for my day to day training.');
        $article->makeChild('p', ' This involves measuring some value that is correlated to exercise intensity.'
                . ' Examples are pace, heart rate and power. For each measure that we use we need to calibrate it to our own '
                . 'performance if it is to help us prescribe exercise intensity. This typically involves finding the maximum '
                . 'steady value that we can sustain for a certain period of time. Once we have this, we can develop a system of '
                . 'zones based on ranges described in percentages of this value. One example from running would be something called '
                . 'Threshold Pace or TP (for the moment ignore the details, it is just an objective datum on which to hang zones). '
                . 'This is usually predicted from the maximum steady pace that we can achieve during a 30 minutes field test. ');

        $article = $this->body->makeChild('article', ' ');

        $article->makeChild('h2', 'Objective Intensity Measures');
        $article->makeChild('h3', 'Heart Rate Zones');
        $article->makeChild('p', ' Heart rate is probably the second oldest objective measure of effort coming after pace. The development of and ready availability of wearable heart rate monitors means that this is often the first objective measure of exercise intensity used by athletes new to structured training. There is something very reassuring about using heart rate. But how do you work out zones of training intensity based on it? There are numerous schemes available, some better than others. Heart rate does however have its limitations which will be discussed at the end of this section. ');
        $article->makeChild('h4', 'Maximum Heart Rate based zones. ');
        $p = $article->makeChild('p', ' ');
        $p->makeChild('a', 'Polar HR Scheme', ['class' => 'Button', 'href' => 'https://www.polar.com/fr/smart-coaching/what-are-heart-rate-zones?gclid=Cj0KCQjwvaeJBhCvARIsABgTDM6pCOGrvgAitBt4_iDoQa4aas6HMbAMluJnuhBA_A2tGN3WupnHggQaAoVIEALw_wcB',]);
        $article->makeChild('img', '', ['src' => '../Images/Polar.png', 'class' => 'width50 center',]);
        $article->makeChild('h4', 'Heart Rate Reserve based zones. ');
        $p = $article->makeChild('p', ' ');
        $p->makeChild('a', ' Karvonen ', ['class' => 'Button', 'href' => 'https://www.running-addict.fr/conseil-running/formule-de-karvonen-calcul-zone-cardiaque/',]);
        $article->makeChild('img', '', ['src' => '../Images/Polar.png', 'class' => 'center width50',]);
        $article->makeChild('p', ' So if we use Karvonen zones we need to know two things, resting heart rate and maximum heart rate. Resting heart rate is straightforward enough. âThe problem is how do you find out your maximum heart rate? ');
        $article->makeChild('h4', 'Maximum Heart Rate');
        $article->makeChild('p', ' I have seen athletes confound maximum heart rate with the maximum heart rate achieved during routine training. They are different! The latter is easily available on modern devices but is likely to be quite a bit lower than your true maximum heart rate. If you base your zones on this value you may get an easy ride but may not improve as much as you should. ');
        $article->makeChild('p', ' Formula methods provide an approximation to your maximum heart rate. Some are better than others. None are perfect. The most commonly used (and worst) predicts your maximum heart rate to be: ');
        
        //
        $article->addChild(MathParser::card('220-age','', '(in years)'));
        //
        
        $article->makeChild('p', 'I believe it is so popular because it is easy to remember and the calculation only requires a simple subtraction. I would not however recommend using this. A better approximation is that developed by Tanaka. It uses: ');
        
        //
        $article->addChild(MathParser::card('209-0.7*age','', '(in years)'));
        //
        
        $p = $article->makeChild('p', ' Even better approximations have been developed which also take in to account gender. These approximations are great for predicting the average maximum heart rate for a particular age ');
        $p->makeChild('span', 'in groups of people', ['style' => 'font-weight: bold;font-style: italic;',]);
        $p->makeChild('span', 'particular individual', ['style' => 'font-weight: bold;font-style: italic;',]);
        $article->makeChild('img', '', ['class' => 'center width650', 'src' => '../Images/AgePredictedMaxHR.png', 'alt' => 'pic',]);
        $article->makeChild('div', 'Source: Age-predicted maximal heart rate in healthy subjects: The HUNT Fitness Study', ['class' => 'centredBoldText',]);
        $article->makeChild('p', ' If you are to use maximum heart rate as your datum on which to base your zone values then unfortunately you really need to measure it. This is an uncomfortable and stressful thing to do. You need to be confident that you are in good health. Unless you are young with no adverse family or personal medical history you should consult your doctor first. ');
        $article->makeChild('p', ' So are you stuck with having to actually measure your maximum HR with a field test? Thankfully not, and here is why.â There is a much better datum on which to base your training zones. It is one that is applicable across all endurance sports and all measures of performance. It has a sound physiological bases and will ensure your training zones target that which can be improved the most. ');
        $article->makeChild('h3', 'Lactate Threshold');
        $p = $article->makeChild('p', ' The lactate threshold is a supremely important physiological event for endurance athletes. It is the intensity of exercise at which lactic acid levels measured in blood increase sharply on further increasing intensity. This is because if we progressively increase exercise intensity we are no longer able to get oxygen at a sufficient rate to supply all the energy needs of an activity from the oxidation of glucose alone (aerobic metabolism). Additional energy must come from the splitting glucose into lactate (glycolysis, anaerobic metabolism). Note that aerobic metabolism continues maximally but with an increasing additional anaerobic component with increasing work intensity. Although the requirement for energy is met, it comes at a heavy cost. Lactic acid accumulation and associated blood pH reduction limits further activity. It can only be cleared in a process that utilizes oxygen, and so cannot be eliminated until we reduce levels of activity again. (This is known as the oxygen debt). The other problem is that glycolysis is highly inefficient and produces only a fraction (about ');
        $p->makeChild('sup', '1');
        $p->makeChild('sub', '15');
        $h4 = $article->makeChild('h4', 'VO');
        $h4->makeChild('sub', '2');
        $h4->makeChild('sub', '2');
        $p = $article->makeChild('p', ' VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p = $article->makeChild('p', ' The graph below demonstrates the relationship between exercise intensity, VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $article->makeChild('img', '', ['class' => 'centerImage width90', 'src' => '../Images/LactateThreshold.png', 'alt' => 'pic',]);
        $h4 = $article->makeChild('h4', 'VO');
        $h4->makeChild('sub', '2');
        $p = $article->makeChild('p', ' There is clearly a relationship between VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p = $article->makeChild('p', ' VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $article->makeChild('img', '', ['style' => 'float:right; width: 40%; margin: 10px 0px 20px 50px;', 'src' => '../Images/Lactate.png',]);
        $p = $article->makeChild('p', ' Training makes lactate elimination more efficient. This means that blood lactate is lower for a given intensity of exercise below VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p = $article->makeChild('p', ' The biggest improvements in performance for most endurance athletes stem from being able to get close to and sustain activity just below VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2(max)');
        $article->makeChild('iframe', '', ['src' => 'https://www.youtube.com/embed/kZLaxdSr3c0', 'height' => '336', 'title' => 'YouTube video player', 'frameborder' => '0', 'allowfullscreen' => '',]);
        $p = $article->makeChild('p', ' To demonstrate this I have produced this animation with VO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');

        $article = $this->body->makeChild('article');
        $aside = $article->makeChild('aside', ' MLSS - Maximum Lactate Steady State');
        $article->makeChild('h4', 'What about MLSS, OBLA, AT, VT etc...');

        $article->makeChild('p', ' Of course a plot of lactate vs. intensity of activity (power, flat pace etc...) does not have a "point" where lactate suddenly starts rising. It is a curve, though admittedly with a hockey stick shape which is more noticeable in a trained athlete. We can look at a curve and say - yep - it really starts rising quickly there but if we are to ascribe this to a certain intensity level we need a rule to find a point on the curve. To do this we need a definition. This is where things get messy as there is no single definition! There are at least 20 different definitions of lactate threshold in published research and a multitude of synonyms to describe these. We might choose a threshold level of lactate but then which level? Lactate at rest hovers around 1 (mmol/l). Definitions using a threshold level seem to range from 2 to 4. These assume that the level of lactate has reached a constant value at a particular steady exercise intensity (and so measurements are taken after a suitable delay following changing to this intensity). ');
        $p = $article->makeChild('p', ' But we can then choose to bring in a time element and define the lowest intensity where blood lactate does ');
        $b = $p->makeChild('b');
        $b->makeChild('i', 'not');
        $p = $article->makeChild('p', ' But what about all the other thresholds? Anaerobic Threshold (AT) is merely a synonym of Lactate Threshold. The Ventilatory Threshold (VT) is the "point" at which the graph of ventilation vs. work intensity quickly steepens. It does so because, in addition to the CO');
        $p->makeChild('sub', '2');
        $p->makeChild('sub', '2');
        $article->makeChild('p', ' So we see that there are really only two types of definition of lactate threshold. One is based on a particular (arbitrary and variable) threshold value of a steady state blood lactate. The other is based on the presence or absence of a steady state level of blood lactate. The other definitions are either synonyms or surrogates of lactate threshold. The difference between lactate thresholds derived using these definitions will reduce with training as the curve becomes increasingly hockey stick shaped and so becomes decreasingly relevant. The vast majority of people will in any case use a field test or even historic data and algorithm to estimate their lactate threshold. From a practical training point of view, I believe they can be considered to be the same. ');
        $article->makeChild('h4', 'Training Variety and Lactate Threshold');
        $article->makeChild('p', ' Of course training and improving the efficiency of lactate elimination is not the only element of training. We also need to do activities that improve our strength, core stability, flexibility, endurance and efficiency. We need to train our neuromuscular system. We need train ourselves to tolerate a degree of discomfort as this is invariably present in competition. These are all necessary, but without training the lactate system, these would of themselves be insufficient for success in fast endurance activities. ');
        $article->makeChild('p', ' We also need to know where our lactate threshold is in order to avoid it! Training near lactate threshold and also the avoidance of training near lactate threshold are both crucially important to polarized training. Having a system of zones of intensity that are based on a reasonably current and representative field test of lactate threshold allow us to do this with confidence. ');
        //$article->makeChild('div', 'P R O B E', ['style'=>'color:white; background:red; font-size:2em; padding:1ch;']);

        $grid = $article->makeChild('div', '', ['class' => 'image-grid']);
        $grid->makeChild('img', '', ['src' => '../Images/EasyRun.png']);
        $grid->makeChild('img', '', ['src' => '../Images/HardRun.jpg']);

        $article->makeChild('h4', 'Lactate Threshold vs. FTP, rFTPw, LTHR etc...');
        $p = $article->makeChild('p', ' But what about all the different threshold values out there? Pace, heart rate and power for running, cycling and swimming? Well, they are all doing the same thing. The thing to keep in mind throughout this apparent complexity is that there is only one physiological event taking place in us as we reach lactate threshold. ');
        $p->makeChild('span', 'We just have to calibrate each sport and metric combination relative to this.', ['style' => 'font-weight: bold; font-style: italic;',]);
        $article->makeChild('h4', 'Methods for Determining Lactate Threshold');
        $article->makeChild('img', '', ['src' => '../Images/LactateThresholdMeasurement.png', 'style' => 'width: 100%;',]);
        $article->makeChild('p', ' ââOK. How do we find out our lactate threshold? ');
        $article->makeChild('h5', 'Measure Directly');
        $p = $article->makeChild('p', ' Of course the gold standard would be to measure blood lactate and our metric of interest (HR, pace, power) while doing a particular activity (run, bike, swim) as we progressively increase activity and find the point where blood lactate starts to sharply increase. This is typically done in a laboratory along with measurement of VO');
        $p->makeChild('sub', '2');
        $article->makeChild('h5', 'Estimate with a Field Test');
        $article->makeChild('p', ' Fortunately there are field tests that can give estimated values that are very well correlated with laboratory measured values. We can also do them every few weeks as our training progresses to recalibrate our training zones. ');
        $article->makeChild('p', ' After a suitable warm up you merely need to start a workout and maintain a constant maximum effort over 30 minutes in your chosen sport. This is because 30 minutes has been found to be the maximum time that most athletes can keep parked on their lactate threshold during an activity without flagging. It is important to try to keep a constant maximum pace during the test, hard but this comes with practice. For a particular sport do this with all your sensors so that you can assign a threshold value to all of them in one session. Virtually every watch website allows you to get the average heart rate, pace or power during a selected thirty minute interval, and with the exception of heart rate, this provides your threshold value. ');
        $article->makeChild('p', " So if we were to do this successfully for running with a gps watch, a heart rate strap and a Stryd foot pod we could at once find out our running threshold pace, heart rate and power. Unfortunately the terminology is not consistent between measures and sports. We have TP (threshold pace), FTP (functional threshold power) and LTHR (lactate threshold heart rate). These can be prefixed with the sport and post-fixed with the unit of measure, so rFTPw is Jim Vance's running functional threshold power in Watts (I guess it is a sub-scripted capital W). The important thing is not to get hung up on the terminology but rather to understand the underlying concept. ");
        $article->makeChild('h5', 'Lactate Threshold Heart Rate - LTHR');
        $article->makeChild('p', ' Because heart rate lags behind changes in activity intensity it is customary in a 30 minute field test to discard the first 10 minutes of readings. You do a 30 minute test but it is the average of the last 20 minutes that is used to estimate LTHR. ');
        $article->makeChild('h5', 'The Non-Linearity of Heart Rate in relation to Exercise Intensity and Duration of Activity');
        $article->makeChild('p', ' Heart rate above resting heart rate exhibits an approximately linear relationship to exercise intensity (running pace, cycling power) in most but not all people. Sigmoidal responses are seen. There is frequently, though not invariably, a deflection point which may correspond with lactate threshold (Conconi). The problem is the scale gets rather squashed as we approach maximum heart rate and our lactate threshold heart rate may only be 10 - 20 beats lower than this leaving little dynamic range to describe intensity above threshold. If we combine these problems with the slow response of heart rate to sudden increases or decreases in training intensity (of the order of 20-30 seconds) and the slow drift up of heart rate at the same training intensity beyond 10-30 minutes, we can see that the range of usefulness is really very limited. ');
        $article->makeChild('p', ' It is easy to see some of the limitations of using heart rate. If we attempted to keep a constant heart rate during a phase of activity we simply could not achieve this in the first 20 to 30 seconds. It is therefore not of value for short intervals. If we kept it constant over a longer phase we would be working harder at the start and have an easier time towards the end of the phase. ');
        $article->makeChild('h5', 'Estimate from Accrued Ordinary Workout Data');
        $article->makeChild('p', " Many sports watches and some foot pods use third proprietary algorithms to predict lactate threshold and other quasi threshold metrics from routine data. Examples are Firstbeat used on a variety of watches including Garmin, Coros's Evolab and Stryd's auto-CP. Typically they would use a model such as critical power or speed (see the critical power section below) to predict these. Such a model needs a sufficient quantity of variable durations near to intensity limits data to really work and without this any prediction will be inaccurate. These results are typically given as a single value rather than a range (to reflecting the uncertainty in prediction) and we should be wary of information arising from a complex model presented in this way. It is better that we know what we don't know! ");
        $article->makeChild('h3', 'Zones, Zones and More Zones');
        $article->makeChild('p', ' There are a large number of zone (zonal) systems out there resulting from combinations of activity type, metric and authority (coach, sporting organisation, equipment provider). Each zone is a range of prescribed training intensity. To have any meaning they need to be related to some measured significant physiological datum in the athlete and are expressed as percentages of this datum. In endurance sports it makes most sense to relate these to Lactate Threshold. ');
        $article->makeChild('p', ' If we compare the following two examples, lactate threshold zones from different authorities: ');
        $article->makeChild('p', " Although sharing the same common structure, zones as ranges of percentages of a threshold value, they are rather different. Running at the very top of Stryd zone 3 would be running right on your lactate threshold whereas cycling at the very top of Joe Friel's zone 3 detailed above would be significantly under lactate threshold. ");
        $article->makeChild('h4', 'Critical Power');
        $article->makeChild('a', 'Critical Power', ['class' => 'Button', 'href' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5070974/',]);
        $article->makeChild('p', ' I feel that I should now discuss critical power. This is a fascinating concept as, unlike lactate threshold, it merely observes performance without any need for a metabolic correlate. It works like this: ');
        $figure = $article->makeChild('figure', ' ', ['class' => 'image-grid',]);
        $figure->makeChild('img', '', ['src' => '../Images/PowerCurve.png',]);
        $figure->makeChild('img', '', ['src' => '../Images/PowerLine.png',]);
        $figure->makeChild('figcaption', ' A typical power-duration curve and line for an athlete. ');
        $article = $this->body->makeChild('article');

        $article->makeChild('aside', ' It is remarkable that this relationship seems universal across species and so must represent '
                . 'something quite fundamental. ');
        $article->makeChild('p', ' There is a threshold intensity of training, the critical power, below which there is no time limitation of activity.'
                . ' Above this threshold, training is time limited. The more intense the training above this threshold, '
                . 'the shorter the possible duration of activity. It can be shown that a very simple equation describes this relationship, '
                . 'which is commonly referred to as a power curve, and is stated below. ');
        
        //
        $card = $article->addChild(MathParser::card('t=w_a/{p-p_c'));
        //
        //$article->addChild(MathParser::card('t=p^2-p_c/(2+6/7)'));
        //
        
        $aside = $card->makeChild('aside');
        $aside->makeChild('div','w<sub>a</sub> = anaerobic');
        $aside->makeChild('div', 'two');
        
        
        
        $article->makeChild('sub', 'c');
        $article->makeChild('sub', 'a');
        $article->makeChild('sub', 'c');
        $article->makeChild('div', '', ['style' => 'clear:both;',]);
        $p = $article->makeChild('p', ' w');
        $p->makeChild('sub', 'a');
        $article->makeChild('sub', 'c');
        $article->makeChild('sub', 'a');
        $article->makeChild('sub', 'c');
        $p = $article->makeChild('p', ' We just need to fit a line between these points. The gradient is w');
        $p->makeChild('sub', 'a');
        $p->makeChild('sub', 'c');
        $article->makeChild('img', '', ['class' => 'center width90', 'src' => '../Images/MyPowerCurves.png',]);
        $article->makeChild('p', ' Luckily modern watch and web site technology allow an individual to accumulate a large data set of power and duration data pairs. The upper boundary of these, if recent enough, indicates the general shape of your power curve. It has to be realized however that in general this upper boundary of all these data pairs will be lower than your "true" power curve. This is because from your everyday training you may not have sufficient maximum effort-duration data pairs at all intensities to fully flesh it out and of course any effort-duration pair is in the context of a complete run and therefore cannot utilize all anaerobic reserve energy or you would stop! The above shows my running power curve over several years. It was only in 2019 that my training was varied enough (a structured training plan with very short intervals, short intervals, longer intervals, tempo runs, long steady runs and recovery runs as per 80:20 running) to push the curve out towards my probable true power curve. However derived, if I have a good estimate of my power curve, I can Tools::interpolate a value for a duration of 30 minutes. This value corresponds to my lactate threshold. ');
        $article->makeChild('p', ' For a steady power of 60 minutes or more in duration the theoretical power-duration curve is very close to the horizontal plot of CP. This has, therefore, been chosen as a surrogate of CP but it will be a slightly higher value. To muddy the waters further this is variously described as CP or Functional Threshold Power - FTP. FTP is a misnomer as it is not a threshold as it is neither CP nor power at LT. If we accept this definition of FTP it must, from the concept of critical power and the basis of field tests of LT, be lower than power than power at LT. ');
        $article->makeChild('p', ' It would be good to have a simple descriptive system to describe predicted or measured steady state power-duration values rather than a plethora of confusing and ambiguous terms. ');
        $article->makeChild('sub', 'â');
        $article->makeChild('sub', '60');
        $article->makeChild('sub', '30');
        $article->makeChild('h5', 'The Limitations of the Critical Power Concept');
        $article->makeChild('p', ' It is easy to see that this idea fails for very short efforts. The power curve values are simply unrealistically high and approach infinite power as the duration approaches zero. This is nonsense. It is obvious that there is an upper limit of power output related to strength and neuromuscular conditioning. It is also clear that on very long runs we eventually stop because we are exhausted not because we have exhausted some anaerobic reserve capacity. The relationship breaks down at the extremes of both very short and very long durations. ');
        $article->makeChild('h4', 'Critical Flat Speed');
        $p = $article->makeChild('p', ' There is linear relationship between power and speed when running on the flat S = k Ã P. From this it can be seen that there must be a critical speed, just as there is a critical power S');
        $p->makeChild('sub', 'c');
        $p->makeChild('sub', 'a');
        $p->makeChild('sub', 'r');
        $article->makeChild('sub', 'c');
        $article->makeChild('sub', 'r');
        $article->makeChild('sub', 'a');
        $p = $article->makeChild('p', ' This is simpler than a power curve, a straight line. One way to think of this is that we cover the race distance with the product of the race duration times our critical speed combined with our anaerobic reserve distance! Two flat races at different distances are an ideal way of finding your critical speed and anaerobic reserve distance. If we plot recent race distances vs. duration, a fitted line will have a slope of S');
        $p->makeChild('sub', 'c');
        $p->makeChild('sub', 'a');
        $article->makeChild('sub', '1');
        $article->makeChild('sub', '2');
        $article->makeChild('sub', 'a');
        $article->makeChild('sub', '1');
        $article->makeChild('sub', 'a');
        $article->makeChild('p', " This differs from Peter Riegel's formula: ");
        $article->makeChild('sub', '1');
        $article->makeChild('sub', '2');
        $article->makeChild('sub', '1');
        $article->makeChild('sup', '1.07');
        $article->makeChild('p', ' The formula based on critical flat speed and anaerobic reserve distance under-estimates race times for longer distances such as the marathon. It also makes absurdly fast predictions for very short distances of say 400m or less. The Riegel formula over-estimates time for short distances of around 1500m. ');
        $p = $article->makeChild('p', ' My most recent race distances are a half-marathon in 113 minutes and a 10k in 53 minutes. It is easy to see that my S');
        $p->makeChild('sub', 'c');
        $p->makeChild('sub', 'a');
        $article->makeChild('h3', "Don't Mix Things Up");
        $article->makeChild('p', " Of course each authority is keen to promote their zonal system as best. In reality there is no good evidence to promote one scheme over another. I think the main thing to consider is if you choose to use a training plan from a particular coach you need to use his zone scheme too. To not do so would be, by analogy, the equivalent of transferring a tune, note for note, line by line, into a different musical scale. It probably wouldn't sound good!ââ ");
        $article->makeChild('p', ' In reality you might undertrain or worse overtrain and injure yourself. ');
        $article->makeChild('img', '', ['class' => 'center width50', 'src' => '../Images/tema.png',]);
        $article->makeChild('h3', 'Now Mix Things Up');
        $article->makeChild('p', ' We have covered the physiological basis of training zones and how, for endurance athletes, the best choice of basis for these is lactate threshold (pace, power, heart rate). With this objective basis we can reliably know where the intensity of our training is positioned in relation to this important datum. It allows us to both target lactate threshold and just as importantly to avoid it. It allows us to calculate our training load from a wide variety of activities. This informs our future training. ');
        $article->makeChild('p', '.');
        
    }
}
