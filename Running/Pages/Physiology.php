<?php

class Physiology extends Page {

    function __construct() {
        parent::__construct('about');
    }

    protected function buildPage(): void {
        parent::buildPage();

        $html = $this->html;
        $head = $this->head;
        $body = $this->form;

        $section = $body->addChild(new Tag('section', ['id' => 'legacy_4cbb686240b5',]));

        $article = $section->addChild(new Tag('article'));
        $h2 = $article->addChild(new Tag('h2'));
        $h2->addText('Introduction');
        $figure = $article->addChild(new Tag('figure', ['style' => 'width:25%;float: right; margin: 10px 0px 20px 50px; ',]));
        $img = $figure->addChild(new Tag('img', ['src' => '../Images/Zatopek.png', 'style' => 'width:100%;border-radius: 30px;',]));
        $figcaption = $figure->addChild(new Tag('figcaption'));
        $figcaption->addText('Emile Zatopek, Courtesy of Ghetty Images');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Phased Zonal training (PZT) of which Interval Training is but one example uses the idea of carrying out an activity in different zones of intensity during different phases of a workout.  
                A phase involves the athlete working at a certain intensity for a specified duration or distance.  
                A complete workout is a contiguous series of different prescribed phases.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('But what is the point of PZT? 
                Well, if you go out and run the same same distance at the same level of effort each day then you will quite quickly reach a point where you stop improving. 
                Of course if you are happy with this then fine. 
                I was and ran like this for decades, enjoying the aesthetics of running and not being particularly interested in improvement or competition. 
                On the other hand if you wish to compete, to reach your full potential you are going to have to use a different strategy and "polarise" your training. 
                This involves doing a mixture of long low intensity sessions,  shorter "tempo" training and highly intense interval training interspersed with periods of rest, cross training, core stability, strength and flexibility training. 
                You will also need to progressively increase the intensity of your training while avoiding injury. 
                Carefully controlling the duration and intensity of your effort with training zones both within and between workouts is essential.');
        $p = $article->addChild(new Tag('p'));
        $a = $p->addChild(new Tag('a', ['class' => 'buttonRight', 'href' => 'https://www.youtube.com/watch?v=VkDYXUI1x08',]));
        $a->addText('80 20 Running');
        $p->addText('(I strongly recommend reading a copy of "80 20" Running by Matt Fitzgerald which provides both an interesting historic background and the scientific basis of this type of training)');
        $p = $article->addChild(new Tag('p'));
        $p->addText('But what are zones?  
                What is their basis?  
                There are numerous schemes available.  
                So many in fact as to overwhelm someone starting out with structured training.  
                What scheme should you choose?  Luckily there is evidence that it does not really matter that much.  
                The most important thing is that your training has a structure and is varied.  
                It should both progressively improve performance and avoid injury. 
                For elite athletes however the margin between performance improvement and injury is very narrow indeed and objective measures of the intensity of training are needed.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Some schemes are in my opinion of more value than others.  They are discussed below.');
        $h2 = $article->addChild(new Tag('h2'));
        $h2->addText('Subjective Exercise Intensity "Measures"');
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('RPE Scales');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Historically the earliest exercise intensity  zones were based on scales of "Rate" of Perceived Exertion (RPE).  
                An example would be the scheme proposed and developed by Borg. 
                (The slightly odd presentation was so the number associated with the description, times ten, approximates to a typical heart rate in a young athlete).
                Although of value in the absence of a more objective measures and with some evidence of a weak correlation with objective exertion intensities (when compared with heart rate) the scales are overly granular for a subjective measure.  
                They give a sense of precision that is simply not there.  
                Personally I have never felt able to accurately judge my level of effort during 40 yrs of road running.  
                I think most athletes will have on occasion found running a familiar route much harder than usual and then been surprised that it took no more time than usual to complete it.  
                Subjective estimates of intensity are influenced by so many factors including the complexity of the human psyche.  
                I doubt any scheme, however sophisticated, could adequately account for that!');
        $table = $article->addChild(new Tag('table', ['class' => 'tableStyle3',]));
        $caption = $table->addChild(new Tag('caption'));
        $caption->addText('Borg\'s Rating of Perceived Exertion (RPE) Scale');
        $tr = $table->addChild(new Tag('tr'));
        $th = $tr->addChild(new Tag('th'));
        $th->addText('Perceived Exertion Rating');
        $th = $tr->addChild(new Tag('th'));
        $th->addText('Description of Exertion');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightblue',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('6');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('No exertion; sitting and resting');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightblue',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('7');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Extremely light');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightgreen',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('8');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightgreen',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('9');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Very light');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightgreen',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('10');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: lightgreen',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('11');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Light');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: yellow',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('12');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: yellow',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('13');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Somewhat hard');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: yellow',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('14');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: yellow',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('15');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Hard');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: yellow',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('16');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: orange',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('17');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Vary hard');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: orange',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('18');
        $td = $tr->addChild(new Tag('td'));
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: orange',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('19');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Extremely hard');
        $tr = $table->addChild(new Tag('tr', ['style' => 'background-color: orange',]));
        $td = $tr->addChild(new Tag('td'));
        $td->addText('20');
        $td = $tr->addChild(new Tag('td'));
        $td->addText('Maximal exertion');
        $p = $article->addChild(new Tag('p'));
        $p->addText('I have found the development of objective physiological measures far more useful for my day to day training.​​');
        $p = $article->addChild(new Tag('p'));
        $p->addText('This involves measuring some value that is correlated to exercise intensity.  
                Examples are pace, heart rate and power. 
                For each measure that we use we need to calibrate it to our own performance if it is to help us prescribe exercise intensity. 
                This typically involves finding the maximum steady value that we can sustain for a certain period of time.  
                Once we have this, we can develop a system of zones based on ranges described in percentages of this value.  
                One example from running would be something called Threshold Pace or TP (for the moment ignore the details, it is just an objective datum on which to hang zones). 
                This is usually predicted from the maximum steady pace that we can achieve during a 30 minutes field test.');
        $h2 = $article->addChild(new Tag('h2'));
        $h2->addText('Objective Intensity Measures');
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('Heart Rate Zones');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Heart rate is probably the second oldest objective measure of effort coming after pace.  
                The development of and ready availability of wearable heart rate monitors means that this is often the first objective measure of exercise intensity used by athletes new to structured training. 
                There is something very reassuring about using heart rate. 
                But how do you work out zones of training intensity based on it?  
                There are numerous schemes available, some better than others.  
                Heart rate does however have its limitations which will be discussed at the end of this section.');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Maximum Heart Rate based zones.');
        $p = $article->addChild(new Tag('p'));
        $a = $p->addChild(new Tag('a', ['class' => 'buttonRight', 'href' => 'https://www.polar.com/fr/smart-coaching/what-are-heart-rate-zones?gclid=Cj0KCQjwvaeJBhCvARIsABgTDM6pCOGrvgAitBt4_iDoQa4aas6HMbAMluJnuhBA_A2tGN3WupnHggQaAoVIEALw_wcB',]));
        $a->addText('Polar HR Scheme');
        $p->addText('This is the simplest of schemes and produces zones as percentages of maximum heart rate.  
                An example would be those pioneered by Polar.  
                Although giving training a structure the zones are rather arbitrary and I think are based on the wrong datum, but more of that in a bit.');
        $img = $article->addChild(new Tag('img', ['src' => '../Images/Polar.png', 'class' => 'centerImage33',]));
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Heart Rate Reserve based zones.');
        $p = $article->addChild(new Tag('p'));
        $a = $p->addChild(new Tag('a', ['class' => 'buttonRight', 'href' => 'https://www.running-addict.fr/conseil-running/formule-de-karvonen-calcul-zone-cardiaque/',]));
        $a->addText('Karvonen');
        $p->addText('A better scheme is one that takes into account your resting heart rate in addition to your maximum heart rate and works out zones from percentages of the difference (heart rate reserve) between these two values.  
                This was developed by Karvonen.                 
                We must be careful to note the same 5 zone descriptions and percentages as above but percentages of something
                quite different!  The Karvonen zones are more tightly packed, spanning just the physiological range in heart rate.  This makes more sense.  
                We can see that zones bearing the same labels may represent quite different values.');
        $img = $article->addChild(new Tag('img', ['src' => '../Images/Polar.png', 'class' => 'centerImage33',]));
        $p = $article->addChild(new Tag('p'));
        $p->addText('So if we use Karvonen zones we need to know two things, resting heart rate and maximum heart rate.
                Resting heart rate is straightforward enough.  ​The problem is how do you find out your maximum heart rate?');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Maximum Heart Rate');
        $p = $article->addChild(new Tag('p'));
        $p->addText('I have seen athletes confound maximum heart rate with the maximum heart rate achieved during routine training.  
                They are different!  
                The latter is easily available on modern devices but is likely to be quite a bit lower than your true maximum heart rate.  
                If you base your zones on this value you may get an easy ride but may not improve as much as you should.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Formula methods provide an approximation to your maximum heart rate.  
                Some are better than others.  
                None are perfect. 
                The most commonly used (and worst) predicts your maximum heart rate to be:');

        $article->addChild(MathParser::card("220 - age, ('in years')"));

        $p = $article->addChild(new Tag('p'));
        $p->addText('I believe it is so popular because it is easy to remember and the calculation only requires a simple subtraction.  
                I would not however recommend using this.
                A better approximation is that developed by Tanaka.  It uses:');

        $article->addChild(MathParser::card("209 - 0.7 * age, ('in years')"));

        $p = $article->addChild(new Tag('p'));
        $p->addText('Even better approximations have been developed which also take in to account gender.
                These approximations are great for predicting the average maximum heart rate for a particular age');
        $span = $p->addChild(new Tag('span', ['style' => 'font-weight: bold;font-style: italic;',]));
        $span->addText('in groups of people');
        $p->addText(', 
                but not so good at predicting maximum heart in a');
        $span = $p->addChild(new Tag('span', ['style' => 'font-weight: bold;font-style: italic;',]));
        $span->addText('particular individual');
        $p->addText('.  
                The following plot, from the HUNT Fitness Study, shows why these best fit linear approximations are poor predictors of individual maximum heart rate.
                For a particular age the spread of actual maximum heart rate values is wide.
                You are unlikely to rest on the predictive line and may fall far away from it.
                If you have trained for decades thus reducing the age related decline in maximum heart rate
                you will almost certainly be an outlier with a much higher than predicted value.');
        $img = $article->addChild(new Tag('img', ['class' => 'centerImage66', 'src' => '../Images/AgePredictedMaxHR.png', 'alt' => 'pic',]));
        $div = $article->addChild(new Tag('div', ['class' => 'centredBoldText',]));
        $div->addText('Source: Age-predicted maximal heart rate in healthy subjects: The HUNT Fitness Study');
        $p = $article->addChild(new Tag('p'));
        $p->addText('If you are to use maximum heart rate as your datum on which to base your zone values then unfortunately you really need to measure it. 
                This is an uncomfortable and stressful thing to do.
                You need to be confident that you are in good health.
                Unless you are young with no adverse family or personal medical history you should consult your doctor first.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('So are you stuck with having to actually measure your maximum HR with a field test?  
                Thankfully not, and here is why.​  
                There is a much better datum on which to base your training zones.  
                It is one that is applicable across all endurance sports and all measures of performance.  
                It has a sound physiological bases and will ensure your training zones target that which can be improved the most.');
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('Lactate Threshold');
        $p = $article->addChild(new Tag('p'));
        $p->addText('The lactate threshold is a supremely important physiological event for endurance athletes.  
                It is the intensity of exercise at which lactic acid levels measured in blood increase sharply on further increasing intensity.
                This is because if we progressively increase exercise intensity we are no longer able to get oxygen at a sufficient rate to supply all the energy needs 
                of an activity from the oxidation of glucose alone (aerobic metabolism). 
                Additional energy must come from the splitting glucose into lactate (glycolysis, anaerobic metabolism).
                Note that aerobic metabolism continues maximally but with an increasing additional anaerobic component with increasing work intensity.
                Although the requirement for energy is met, it comes at a heavy cost.  
                Lactic acid accumulation and associated blood pH reduction limits further activity.  
                It can only be cleared in a process that utilizes oxygen, and so cannot
                be eliminated until we reduce levels of activity again. (This is known as the oxygen debt). 
                The other problem is that glycolysis is highly inefficient and produces only a fraction (about');
        $sup = $p->addChild(new Tag('sup'));
        $sup->addText('1');
        $p->addText('/');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('15');
        $p->addText(') of the energy produced from the metabolism of glucose with oxygen)
                It is wasteful of your precious glycogen reserves (you don\'t want to walk the last few miles of your marathon!)');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('VO');
        $sub = $h4->addChild(new Tag('sub'));
        $sub->addText('2');
        $h4->addText('and VO');
        $sub = $h4->addChild(new Tag('sub'));
        $sub->addText('2');
        $h4->addText('max');
        $p = $article->addChild(new Tag('p'));
        $p->addText('VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('is oxygen uptake in milliliters of oxygen per kilogram and so is a weight independent measure of how much oxygen an athlete is consuming at some point in time.
                VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max is the maximum oxygen uptake possible in a particular athlete during a maximally intense activity.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('The graph below demonstrates the relationship between exercise intensity, VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText(', VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max and blood lactate.  
                The yellow arrow shows the increase in intensity of an activity and so energy consumption.  
                The red graph shows that initially oxygen consumption (VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText(') keeps pace and we metabolize glucose aerobically and don\'t produce an excess of lactic acid.  
                As the intensity increases further we reach the limit at which our body is able to utilize oxygen (VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max).  
                As we can no longer match our energy consumption through aerobic energy production we have to get part of our energy from glycolysis and lactate starts to rise.  
                This produces the typical "hockey stick" curve which is seen in the real world graph to the right.');
        $img = $article->addChild(new Tag('img', ['class' => 'centerImage90', 'src' => '../Images/LactateThreshold.png', 'alt' => 'pic',]));
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('VO');
        $sub = $h4->addChild(new Tag('sub'));
        $sub->addText('2');
        $h4->addText('max and Lactate Threshold');
        $p = $article->addChild(new Tag('p'));
        $p->addText('There is clearly a relationship between VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max and Lactate Threshold.  
                Above VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max any sustained additional energy production must come from glycolysis 
                The reason the VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('at lactate threshold is not equal to VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max is because the switch over to anaerobic metabolism happens progressively
                below VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max. The rate of switchover is something that is very amenable to training as we will see below.
                We might choose to call the VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('at which lactate levels start to rise appreciably the VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('LT (Lactate Threshold).  
                It is somewhat lower then VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max is heavily genetically determined both in its untrained level and its response to training.  
                The typical person can expect a modest maximal increase of up to 15%.  
                But there are much bigger gains to be made.  
                To understand this requires knowledge of the interrelationship between lactate threshold and VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max as we get fitter.');
        $img = $article->addChild(new Tag('img', ['style' => 'float:right; width: 40%; margin: 10px 0px 20px 50px;', 'src' => '../Images/Lactate.png',]));
        $p = $article->addChild(new Tag('p'));
        $p->addText('Training makes lactate elimination more efficient.  
                This means that blood lactate is lower for a given intensity of exercise below VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max.  
                Of course this reduction in lactate for a particular exercise intensity must ultimately fail as we approach and then exceed VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max and so perhaps 
                frustratingly eventually bump up against our own genetic limitations.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('The biggest improvements in performance for most endurance athletes stem from being able to get close to and sustain activity just below VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max,
                and to do so without rapidly metabolizing glucose (and therefore their glycogen reserves).
                This improvement is as a result of the change in shape of the lactate curve which increasingly develops the so called "hockey stick" with lactate levels only rising when close to VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max.
                The recovery of lactate is an oxygen consuming process and so in part the modest increase in VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max with training results from the 
                improvement in lactate metabolism.
                Representative lactate plots vs. %VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2(max)');
        $p->addText('of an untrained and a trained athlete are shown above right for comparison.');
        $iframe = $article->addChild(new Tag('iframe', ['style' => 'float:right; margin: 10px 0px 20px 50px;', 'src' => 'https://www.youtube.com/embed/kZLaxdSr3c0', 'height' => '336', 'title' => 'YouTube video player', 'frameborder' => '0', 'allowfullscreen' => '',]));
        $p = $article->addChild(new Tag('p'));
        $p->addText('To demonstrate this I have produced this animation with VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('on the x axis and blood lactate on the y.  
                The left hand vertical red line indicates VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('at LT and the right hand one VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max.
                The purple line represents lactate threshold blood lactate.  
                The time element for the animation could be considered the time into your training plan as you progressively become fitter.
                VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max increases a little with training and this moves the whole lactate curve to the right.  
                VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('LT moves more dramatically to the right influenced by this shift and the increasing bend in the curve as lactate elimination improves.  
                The VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('LT datum is far more relevant to endurance athletes and typically an activity of this intensity can be sustained for around half an hour
                which, for many runners, is long enough to complete a 10k race.  
                This is where most of the gain in athletic performance for an endurance athlete comes from
                and this is why the lactate threshold is such an important value on which to base our training zones.');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('What about MLSS, OBLA, AT, VT etc...');
        $aside = $article->addChild(new Tag('aside'));
        $aside->addText('MLSS - Maximum Lactate Steady State');
        $br = $aside->addChild(new Tag('br'));
        $aside->addText('OBLA - Onset of Blood Lactate Accumulation');
        $br = $aside->addChild(new Tag('br'));
        $aside->addText('AT - Anaerobic Threshold');
        $br = $aside->addChild(new Tag('br'));
        $aside->addText('VT - Ventilatory Threshold');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Of course a plot of lactate vs. intensity of activity (power, flat pace etc...) does not have a "point" where lactate suddenly starts rising.
                It is a curve, though admittedly with a hockey stick shape which is more noticeable in a trained athlete.
                We can look at a curve and say - yep - it really starts rising quickly there but if we are to ascribe this to a certain intensity level      
                we need a rule to find a point on the curve.
                To do this we need a definition.
                This is where things get messy as there is no single definition!
                There are at least 20 different definitions of lactate threshold in published research and a multitude of synonyms to describe these.
                We might choose a threshold level of lactate but then which level?  
                Lactate at rest hovers around 1 (mmol/l). 
                Definitions using a threshold level seem to range from 2 to 4.
                These assume that the level of lactate has reached a constant value at a particular steady exercise intensity 
                (and so measurements are taken after a suitable delay following changing to this intensity).');
        $p = $article->addChild(new Tag('p'));
        $p->addText('But we can then choose to bring in a time element and define the lowest intensity where blood lactate does');
        $b = $p->addChild(new Tag('b'));
        $i = $b->addChild(new Tag('i'));
        $i->addText('not');
        $p->addText('reach a steady level but continues to rise.
                This is described as the Onset of Blood Lactate Accumulation or OBLA.
                Conversely, less than a hair\'s breadth below this, is the Maximum Lactate Steady State or MLSS.
                (Though conceptual opposites, the intensities defined in this way are in truth the same, any differences resulting from measurement).

                Some authors use the concept of OBLA (and so MLSS) to define lactate threshold and this is my preferred definition as it is less
                arbitrary than choosing a particular level (definitions of which vary because it is an arbitrary choice).
                Lactate threshold defined in this way will correspond to a higher work intensity than using a simple steady state threshold level.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('But what about all the other thresholds?
                Anaerobic Threshold (AT) is merely a synonym of Lactate Threshold.
                The Ventilatory Threshold (VT) is the "point" at which the graph of ventilation vs. work intensity quickly steepens.
                It does so because, in addition to the CO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('from aerobic metabolism, CO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('is also produced from the buffering of lactate from anaerobic metabolism by bicarbonate.
                It is therefore merely a surrogate for the lactate threshold.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('So we see that there are really only two types of definition of lactate threshold.
                One is based on a particular (arbitrary and variable) threshold value of a steady state blood lactate.
                The other is based on the presence or absence of a steady state level of blood lactate.
                The other definitions are either synonyms or surrogates of lactate threshold.
                The difference between lactate thresholds derived using these definitions will reduce with training as the curve becomes increasingly hockey stick shaped
                and so becomes decreasingly relevant.
                The vast majority of people will in any case use a field test or even historic data and algorithm to estimate their lactate threshold.
                From a practical training point of view, I believe they can be considered to be the same.');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Training Variety and Lactate Threshold');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Of course training and improving the efficiency of lactate elimination is not the only element of training.  
                We also need to do activities that improve our strength, core stability, flexibility, endurance and efficiency. 
                We need to train our neuromuscular system.  
                We need train ourselves to tolerate a degree of discomfort as this is invariably present in competition.
                These are all necessary, but  without training the lactate system, these would of themselves be insufficient for success in 
                fast endurance activities.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('We also need to know where our lactate threshold is in order to avoid it!
                Training near lactate threshold and also the avoidance of training near lactate threshold are both crucially important to
                polarized training.
                Having a system of zones of intensity that are based on a reasonably current and representative field test of lactate threshold 
                allow us to do this with confidence.');
        $table = $article->addChild(new Tag('table'));
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'width: 50%;',]));
        $img = $td->addChild(new Tag('img', ['src' => '../Images/EasyRun.png', 'style' => 'margin-left:auto; margin-right: auto; display:block; width: 93%; height: 93%',]));
        $td = $tr->addChild(new Tag('td', ['style' => 'width: 50%;',]));
        $img = $td->addChild(new Tag('img', ['src' => '../Images/HardRun.jpg', 'style' => 'margin-left:auto; margin-right: auto; display:block; width: 81%; height: 81%; text-align: center;',]));
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'text-align: center; font-size: 70%; font-weight: bold;',]));
        $td->addText('Easy Long Run (with Dogs!)');
        $td = $tr->addChild(new Tag('td', ['style' => 'text-align: center; font-size: 70%; font-weight: bold;',]));
        $td->addText('Hard Run (Cardiff');
        $sup = $td->addChild(new Tag('sup'));
        $sup->addText('1');
        $td->addText('/');
        $sub = $td->addChild(new Tag('sub'));
        $sub->addText('2');
        $td->addText('Marathon Race)');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Lactate Threshold vs. FTP, rFTPw, LTHR etc...');
        $p = $article->addChild(new Tag('p'));
        $p->addText('But what about all the different threshold values out there?  
                Pace, heart rate and power for running, cycling and swimming?  
                Well, they are all doing the same thing. 
                The thing to keep in mind throughout this apparent complexity is that there is only one physiological event taking place in us as we reach lactate threshold.');
        $span = $p->addChild(new Tag('span', ['style' => 'font-weight: bold; font-style: italic;',]));
        $span->addText('We just have to calibrate each sport and metric combination relative to this.');
        $p->addText('(In fact, for convenience, for a particular sport, we can obtain LT HR, LT Pace, LT Power all in one go during a field test)');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Methods for Determining Lactate Threshold');
        $img = $article->addChild(new Tag('img', ['src' => '../Images/LactateThresholdMeasurement.png', 'style' => 'width: 100%;',]));
        $p = $article->addChild(new Tag('p'));
        $p->addText('​​OK.  How do we find out our lactate threshold?');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('Measure Directly');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Of course the gold standard would be to measure blood lactate and our metric of interest (HR, pace, power) while doing a particular activity (run, bike, swim)
                as we progressively increase activity and find the point where blood lactate starts to sharply increase.  
                This is typically done in a laboratory along with measurement of VO');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('2');
        $p->addText('max.  
                In reality few people have access to these tests routinely. 
                As our lactate threshold can increase quite quickly early on in training and we would have to keep revisiting this process periodically as 
                the precise values produced would lose relevance to our current state of conditioning.');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('Estimate with a Field Test');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Fortunately there are field tests that can give estimated values that are very well correlated with laboratory measured values.  
                We can also do them every few weeks as our training progresses to recalibrate our training zones.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('After a suitable warm up you merely need to start a workout and maintain a constant maximum effort over 30 minutes in your chosen sport.  
                This is because 30 minutes has been found to be the maximum time that most athletes can keep parked on their lactate threshold during an activity without flagging.  
                It is important to try to keep a constant maximum pace during the test, hard but this comes with practice.  
                For a particular sport do this with all your sensors so that you can assign a threshold value to all of them in one session.  
                Virtually every watch website allows you to get the average heart rate, pace or power during a selected thirty minute interval, and with the exception of heart rate, this provides your threshold value.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('So if we were to do this successfully for running with a gps watch, a heart rate strap and a Stryd foot pod we could at once find out our running threshold pace, heart rate and power.  
                Unfortunately the terminology is not consistent between measures and sports.  
                We have TP (threshold pace), FTP (functional threshold power) and LTHR (lactate threshold heart rate).  
                These can be prefixed with the sport and post-fixed with the unit of measure, so rFTPw is Jim Vance\'s running functional threshold power in Watts (I guess it is a sub-scripted capital W).  
                The important thing is not to get hung up on the terminology but rather to understand the underlying concept.');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('Lactate Threshold Heart Rate - LTHR');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Because heart rate lags behind changes in activity intensity it is customary in a 30 minute field test to discard the first 10 minutes of readings.
                You do a 30 minute test but it is the average of the last 20 minutes that is used to estimate LTHR.');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('The Non-Linearity of Heart Rate in relation to Exercise Intensity and Duration of Activity');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Heart rate above resting heart rate exhibits an approximately linear relationship to exercise intensity (running pace, cycling power)
                in most but not all people.
                Sigmoidal responses are seen.
                There is frequently, though not invariably, a deflection point which may correspond with lactate threshold (Conconi).
                The problem is the scale gets rather squashed as we approach maximum heart rate and our lactate threshold heart rate may only be
                10 - 20 beats lower than this leaving little dynamic range to describe intensity above threshold.
                If we combine these problems with the slow response of heart rate to sudden increases or decreases in training intensity (of the order
                of 20-30 seconds) and the slow drift up of heart rate at the same training intensity beyond 10-30 minutes, we can see that the
                range of usefulness is really very limited.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('It is easy to see some of the limitations of using heart rate.  
                If we attempted to keep a constant heart rate during a phase of activity we simply could not achieve this in the first 20 to 30 seconds.  
                It is therefore not of value for short intervals.  
                If we kept it constant over a longer phase we would be working harder at the start and have an easier time towards the end of the phase.');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('Estimate from Accrued Ordinary Workout Data');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Many sports watches and some foot pods use third proprietary algorithms to predict lactate threshold and other quasi threshold metrics from routine data.
                Examples are Firstbeat used on a variety of watches including Garmin, Coros\'s Evolab and Stryd\'s auto-CP.
                Typically they would use a model such as critical power or speed (see the critical power section below) to predict these.
                Such a model needs a sufficient quantity of variable durations near to intensity limits data to really work and without this any prediction will be inaccurate.
                These results are typically given as a single value rather than a range (to reflecting the uncertainty in prediction) and we should be wary 
                of information arising from a complex model presented in this way.
                It is better that we know what we don\'t know!');
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('Zones, Zones and More Zones');
        $p = $article->addChild(new Tag('p'));
        $p->addText('There are a large number of zone (zonal) systems out there resulting from combinations of activity type, metric and authority (coach, sporting organisation, equipment provider).  
                Each zone is a range of prescribed training intensity.  
                To have any meaning they need to be related to some measured significant physiological datum in the athlete and are expressed as percentages of this datum.  
                In endurance sports it makes most sense to relate these to Lactate Threshold.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('If we compare the following two examples, lactate threshold zones from different authorities:');
        $table = $article->addChild(new Tag('table', ['style' => 'margin-left:auto;margin-right:auto;width:30ch;border-collapse:collapse;border-width:0px;',]));
        $caption = $table->addChild(new Tag('caption'));
        $caption->addText('Stryd');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#DDC;padding:5px 5px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('Zone');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#DDC;padding:5px 5px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('rFTPw %');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('1');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('70 - 80');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('2');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('81 - 90');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('3');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('91 - 100');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('4');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('101 - 115');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('5');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('116 - 130');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Although sharing the same common structure, zones as ranges of percentages of a threshold value, they are rather different.  
                Running at the very top of Stryd zone 3 would be running right on your lactate threshold whereas 
                cycling at the very top of Joe Friel\'s zone 3 detailed above would be significantly under lactate threshold.');
        $table = $article->addChild(new Tag('table', ['style' => 'margin-left:auto;margin-right:auto;width:30ch;border-collapse:collapse;border-width:0px;',]));
        $caption = $table->addChild(new Tag('caption'));
        $caption->addText('Joe Friel - Bike');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#DDC;padding:5px 5px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('Zone');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#DDC;padding:5px 5px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('LTHR %');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('1');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('2');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('81 - 89');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('3');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('90 - 93');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('4');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('94 - 99');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('5a');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('100 - 102');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('5b');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('103 - 106');
        $tr = $table->addChild(new Tag('tr'));
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:#EED;padding:0px 0px;text-align:center;font-weight:bold;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('5c');
        $td = $tr->addChild(new Tag('td', ['style' => 'background-color:white;padding:20px 0px;text-align:center;border-style:solid;border-width:3px;border-color:#EED;',]));
        $td->addText('> 106');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Critical Power');
        $a = $article->addChild(new Tag('a', ['class' => 'buttonRight', 'href' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC5070974/',]));
        $a->addText('Critical Power');
        $p = $article->addChild(new Tag('p'));
        $p->addText('I feel that I should now discuss critical power.
                This is a fascinating concept as, unlike lactate threshold, it merely observes performance without any need for a metabolic correlate.
                It works like this:');
        $figure = $article->addChild(new Tag('figure', ['class' => 'centerImage90',]));
        $img = $figure->addChild(new Tag('img', ['style' => 'width:45%;', 'src' => '../Images/PowerCurve.png',]));
        $img = $figure->addChild(new Tag('img', ['style' => 'width:45%;', 'src' => '../Images/PowerLine.png',]));
        $figcaption = $figure->addChild(new Tag('figcaption'));
        $figcaption->addText('A typical power-duration curve and line for an athlete.');
        $aside = $article->addChild(new Tag('aside', ['style' => 'width:20%',]));
        $aside->addText('It is remarkable that this relationship seems universal across species and so must represent something quite fundamental.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('There is a threshold intensity of training, the critical power, below which there is no time limitation of activity.
                Above this threshold, training is time limited.
                The more intense the training above this threshold, the shorter the possible duration of activity.
                It can be shown that a very simple equation describes this relationship, which is commonly referred to as a 
                power curve, and is stated below.');


        // equation

        $article->addChild(MathParser::card('t = w_a/{p-p_c}',
                        [
                            't = time',
                            'w<sub>a</sub> = anaerobic reserve power',
                            'p = power',
                            'p<sub>a</sub> = critical power'
                        ]
        ));

        $div = $article->addChild(new Tag('div', ['style' => 'clear:both;',]));
        
        $p->addText('is a constant value with units of energy and could be thought of as representing some depletable anaerobic reserve metabolism. 
                We might give this a name, say anaerobic reserve energy ARE.
                To originally demonstrate that in general the form of the above power curve was correct would have required multiple tests to exhaustion at different intensities.
                Luckily to estimate the the power curve in an individual athlete requires just two tests to exhaustion separated by a suitable recovery period.
                (of course a result from a linear regression of more tests is more likely to reflect your true CP and many people recommend 5 - ouch!).
                We can see this easily if we re-arrange the equation:');

        // equation

        $article->addChild(MathParser::card('p = w_a/{t+p_c}',
                [
                    'p = power',
                    'w<sub>a</sub> = anaerobic reserve power',
                    't = time',
                    'p<sub>c</sub> = critical power'
                ]));

       

        $p = $article->addChild(new Tag('p'));
        $p->addText('We just need to fit a line between these points.
                The gradient is w');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('a');
        $p->addText('and the intercept p');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('c');
        $p->addText('.
                OK, so we might not be keen to do two constant power efforts to exhaustion at two different power levels to determine our current power-duration curve.');
        $img = $article->addChild(new Tag('img', ['class' => 'centerImage50', 'src' => '../Images/MyPowerCurves.png',]));
        $p = $article->addChild(new Tag('p'));
        $p->addText('Luckily modern watch and web site technology allow an individual to accumulate a large data set of power and duration data pairs.
                The upper boundary of these, if recent enough, indicates the general shape of your power curve.
                It has to be realized however that in general this upper boundary of all these data pairs will be lower than your "true" power curve.
                This is because from your everyday training you may not have sufficient maximum effort-duration data pairs at all intensities to fully flesh it out and of
                course any effort-duration pair is in the context of a complete run and therefore cannot utilize all anaerobic reserve energy or you would stop!
                The above shows my running power curve over several years.
                It was only in 2019 that my training was varied enough (a structured training plan with very short intervals, short intervals, longer intervals, tempo runs, long steady runs and recovery runs
                as per 80:20 running) to push the curve out towards my probable true power curve.
                However derived, if I have a good estimate of my power curve, I can Tools::interpolate a value for a duration of 30 minutes.
                This value corresponds to my lactate threshold.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('For a steady power of 60 minutes or more in duration the theoretical power-duration curve is very close to the horizontal plot of CP.
                This has, therefore, been chosen as a surrogate of CP but it will be a slightly higher value.
                To muddy the waters further this is variously described as CP or Functional Threshold Power - FTP.
                FTP is a misnomer as it is not a threshold as it is neither CP nor power at LT.
                If we accept this definition of FTP it must, from the concept of critical power and the basis of field tests of LT, be
                lower than power than power at LT.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('It would be good to have a simple descriptive system to describe predicted or measured steady state power-duration values rather than a plethora of confusing
                and ambiguous terms.');
        $ul = $article->addChild(new Tag('ul'));
        $li = $ul->addChild(new Tag('li', ['style' => 'font-weight: bold; margin-left: 5ch;',]));
        $li->addText('P');
        $sub = $li->addChild(new Tag('sub'));
        $sub->addText('∞');
        $li->addText('&nbsp &nbsp');
        $font = $li->addChild(new Tag('font', ['style' => 'font-size: 75%; font-weight: bold;',]));
        $font->addText('true critical power - CP');
        $li = $ul->addChild(new Tag('li', ['style' => 'font-weight: bold; margin-left: 5ch;',]));
        $li->addText('P');
        $sub = $li->addChild(new Tag('sub'));
        $sub->addText('60');
        $li->addText('&nbsp &nbsp');
        $font = $li->addChild(new Tag('font', ['style' => 'font-size: 75%; font-weight: bold;',]));
        $font->addText('functional threshold power - FTP (also incorrectly referred to as critical power - CP');
        $li = $ul->addChild(new Tag('li', ['style' => 'font-weight: bold; margin-left: 5ch;',]));
        $li->addText('P');
        $sub = $li->addChild(new Tag('sub'));
        $sub->addText('30');
        $li->addText('&nbsp &nbsp');
        $font = $li->addChild(new Tag('font', ['style' => 'font-size: 75%; font-weight: bold;',]));
        $font->addText('lactate threshold power - LTP');
        $h5 = $article->addChild(new Tag('h5'));
        $h5->addText('The Limitations of the Critical Power Concept');
        $p = $article->addChild(new Tag('p'));
        $p->addText('It is easy to see that this idea fails for very short efforts.
                The power curve values are simply unrealistically high and approach infinite power as the duration approaches zero.
                This is nonsense.
                It is obvious that there is an upper limit of power output related to strength and neuromuscular conditioning.
                It is also clear that on very long runs we eventually stop because we are exhausted not because we have exhausted some anaerobic reserve capacity.
                The relationship breaks down at the extremes of both very short and very long durations.');
        $h4 = $article->addChild(new Tag('h4'));
        $h4->addText('Critical Flat Speed');
        $p = $article->addChild(new Tag('p'));
        $p->addText('There is linear relationship between power and speed when running on the flat S = k × P.
                From this it can be seen that there must be a critical speed, just as there is a critical power S');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('c');
        $p->addText('.
                There must also be an anaerobic reserve distance D');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('a');
        $p->addText('or ARD!
                So for a race of distance D');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('r');
        $p->addText('we can state:');
        
        // equation
        
        $article->addChild(MathParser::card('D_r = S_c * t_1 + D_a'));
        
       
        $p = $article->addChild(new Tag('p'));
        $p->addText('This is simpler than a power curve, a straight line.
                One way to think of this is that we cover the race distance with the product of the race duration times our critical speed combined with our anaerobic reserve distance!
                Two flat races at different distances are an ideal way of finding your critical speed and anaerobic reserve distance.
                If we plot recent race distances vs. duration, a fitted line will have a slope of S');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('c');
        $p->addText('and intercept of D');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('a');
        $p->addText('.
                From this line it is easy to Tools::interpolate your lactate threshold speed (30 mins duration) and also 
                predict your race duration for a flat race at another distance.');
        
        // equation
        
        $article->addChild(MathParser::card('t_2 = t_1 * {D_2-D_a}/{D_1-D_a}'));
        
        $p = $article->addChild(new Tag('p'));
        $p->addText('This differs from Peter Riegel\'s formula:');
        
        // equation
        
        $article->addChild(MathParser::card('t_2 = t_1 * (D_2/D_1)^1.07'));
        
        $article->addChild(MathParser::card('(-b+-root{b^2-4*a*c}/{2*a})^2^2'));
      
       
        $p = $article->addChild(new Tag('p'));
        $p->addText('The formula based on critical flat speed and anaerobic reserve distance under-estimates race times for longer distances such as the marathon.
                It also makes absurdly fast predictions for very short distances of say 400m or less.
                The Riegel formula over-estimates time for short distances of around 1500m.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('My most recent race distances are a half-marathon in 113 minutes and a 10k in 53 minutes.
                It is easy to see that my S');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('c');
        $p->addText('= 11 km/h and my D');
        $sub = $p->addChild(new Tag('sub'));
        $sub->addText('a');
        $p->addText('is just 0.283 km.
                I can predict a 5k time of 25 mins 12 seconds and 1500m time of 6 mins 38 seconds.
                It is interesting that my anaerobic reserve distance is less than 1/3 of a kilometer but this would give me
                a predicted 400m time of 38s which would easily make me a world record holder!');
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('Don\'t Mix Things Up');
        $p = $article->addChild(new Tag('p'));
        $p->addText('Of course each authority is keen to promote their zonal system as best.  
                In reality there is no good evidence to promote one scheme over another.  
                I think the main thing to consider is if you choose to use a training plan from a particular coach you need to use his zone scheme too.  
                To not do so would be, by analogy, the equivalent of transferring a tune, note for note, line by line, into a different musical scale.  
                It probably wouldn\'t sound good!​​');
        $p = $article->addChild(new Tag('p'));
        $p->addText('In reality you might undertrain or worse overtrain and injure yourself.');
        $img = $article->addChild(new Tag('img', ['class' => 'centerImage50', 'src' => '../Images/tema.png',]));
        $h3 = $article->addChild(new Tag('h3'));
        $h3->addText('Now Mix Things Up');
        $p = $article->addChild(new Tag('p'));
        $p->addText('We have covered the physiological basis of training zones and how, for endurance athletes, the best choice of basis for these is lactate threshold 
                (pace, power, heart rate).  
                With this objective basis we can reliably know where the intensity of our training is positioned in relation to this important datum.  
                It allows us to both target lactate threshold and just as importantly to avoid it.
                It allows us to calculate our training load from a wide variety of activities.
                This informs our future training.');
        $p = $article->addChild(new Tag('p'));
        $p->addText('.');
    }
}
